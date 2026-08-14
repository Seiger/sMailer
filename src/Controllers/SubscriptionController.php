<?php namespace Seiger\sMailer\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Seiger\sMailer\Models\Subscriber;
use Seiger\sMailer\Services\DomainContext;
use Seiger\sMailer\Services\LanguageContext;

/** Accept public newsletter subscriptions without exposing manager concerns. */
class SubscriptionController
{
    public function store(Request $request): JsonResponse
    {
        $rateLimitKey = $this->rateLimitKey($request);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return response()->json([
                'status' => false,
                'message' => __('Too many subscription attempts. Please try again later.'),
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 3600);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$this->passesRecaptcha($request)) {
            return response()->json([
                'status' => false,
                'message' => __('reCAPTCHA verification failed. Please try again.'),
            ], 422);
        }

        $email = mb_strtolower(trim((string) $validator->validated()['email']));
        $domain = app(DomainContext::class)->current();
        $subscriber = Subscriber::query()->firstOrNew(['domain' => $domain, 'email' => $email]);
        $subscriber->lang = app(LanguageContext::class)->current();

        if ($subscriber->exists && $subscriber->status === 'blocked') {
            return response()->json([
                'status' => false,
                'message' => __('This email address cannot be subscribed.'),
            ], 422);
        }

        $alreadySubscribed = $subscriber->exists && $subscriber->status === 'active';
        $subscriber->status = 'active';
        $subscriber->subscribed_at = now();
        $subscriber->unsubscribed_at = null;
        $subscriber->blocked_at = null;
        $subscriber->save();

        return response()->json([
            'status' => true,
            'message' => $alreadySubscribed
                ? __('You are already subscribed.')
                : __('Thanks — you’re subscribed.'),
        ]);
    }

    /**
     * Validate a v3 token when reCAPTCHA is configured for the current site.
     * Sites without a configured key retain the same form behaviour.
     */
    private function passesRecaptcha(Request $request): bool
    {
        $secret = trim((string)evo()->getConfig('sset_google_recaptcha_secret', ''));

        if ($secret === '') {
            return true;
        }

        $token = trim((string)$request->input('recaptcha_response', ''));
        if ($token === '') {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(5)->post(
                'https://www.google.com/recaptcha/api/siteverify',
                ['secret' => $secret, 'response' => $token]
            );

            $result = $response->object();

            return $response->successful()
                && ($result->success ?? false) === true
                && ($result->action ?? '') === 'newsletter_subscribe'
                && (float) ($result->score ?? 0) >= 0.7;
        } catch (\Throwable) {
            return false;
        }
    }

    private function rateLimitKey(Request $request): string
    {
        return 'smailer:subscribe:' . sha1(implode('|', [
            app(DomainContext::class)->current(),
            (string) $request->ip(),
        ]));
    }
}
