<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
    #copyright{position:fixed;bottom:0;right:0;}
    #copyright img{width:35px;}
    /* configTab */
    .seiger__module-title {
        font-family: 'Roboto', serif;
        font-size: 28px;
        font-style: normal;
        font-weight: 400;
        line-height: 115%;
        padding: 16px initial;
        margin-bottom: 30px;
        border-bottom: 1px solid #EAEAEA;
    }

    .seiger-input-title {
        color: var(--text-text-middle, #63666B);
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        padding-right: 5px;
    }
    .form-control.seiger-input {
        color: var(--text-text-base, #0D0D0D);
        height: 42px;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        padding: 16px 12px;
        border-radius: 6px !important;
    }
    .form-control.seiger-input-btn {
        border-radius: 0px 6px 6px 0px !important;
        border-top: 1px solid #BBB;
        border-right: 1px solid #BBB;
        border-bottom: 1px solid #BBB;
        background-color: var(--secondary-gray, #EAEAEA) !important;
        color: var(--text-text-base, #0D0D0D);
        font-size: 15px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        max-width: 123px;
    }
    .form-control.seiger-input.rounded-left {
        border-radius: 6px 0 0 6px !important;
    }
    .form-row.row {
        margin-right: 0 !important;
    }
    .seiger-btn.seiger-primary-btn {
        border-radius: 2px;
        background: #009891;
        margin: 0 0 32px 0;
        padding: 12px 24px;
        color: #FFF;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        border: none;
        outline: none;
    }
    .seiger-mail-row {
        margin-bottom: 32px;
    }
    .seiger-input-time {
        border-radius: 6px !important;
        border: 1px solid #BBB;
        background:  #FFF;
        max-width: 65px;
        height: 42px;
        color:  #0D0D0D;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        letter-spacing: 1px;
    }
    .seiger-sub-remove {
        background: transparent !important;
        font-size: 18px !important;
    }
    .seiger-inputs-group .input-group:last-of-type{
        margin-bottom: 0 !important;
    }
    .input-group .tox-tinymce {
        width: 100%;
    }
    .tab-row-container {
        background: #CECECF;
    }
    .tab-row .tab a {
        color:  #0D0D0D;
        font-family: 'Roboto';
        font-size: 14px;
        font-weight: 400;
        line-height: 115%;
        text-transform: uppercase;
        padding: 16px;
    }
    .sorting {
        cursor: pointer;
    }
    .sorting:hover {
        background-color: rgba(0,0,0,.075);
    }
    .sorting.sorted {
        background-color: rgba(0,0,0,.075);
    }
    .seiger-subs-conters {
        margin-bottom: 24px;
        margin-top: 8px;
    }
    .seiger-subs-conters-item {
        margin-right: 24px;
    }
    .seiger-subs-status-title {
        font-size: 14px;
        font-weight: 400;
        line-height: 120%;
    }
    .seiger-subs-all {
        color: var(--text-text-base, #0D0D0D);
        font-size: 16px;
        font-weight: 700;
        line-height: 120%;
    }
    .seiger-subs-active {
        color: var(--brand-storiya-green, #009891);
    }
    .seiger-subs-block {
        color: var(--brand-storiya-violet, #5F2D8C);
    }
    .seiger-subs-unsubs {
        color: var(--brand-storiya-pink, #EF4B67);
    }
    .form-control.seiger-subs-input {
        height: 42px;
        font-size: 16px;
        font-weight: 400;
        line-height: 120%;
        padding: 16px 12px;
    }
    .seiger-subs-table thead th, .seiger-subs-table thead th button {
        color: var(--text-text-middle, #63666B);
        font-size: 12px;
        font-weight: 700;
        line-height: 120%;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .seiger-subs-table thead th:first-of-type {
        padding-left: 43px;
    }
    .seiger-subs-table thead th:last-of-type {
        padding-right: 43px;
    }
    .seiger-subs-table tbody tr td {
        color:  #0D0D0D;
        font-size: 14px;
        font-weight: 400;
        line-height: 120%;
    }
    .seiger-subs-table tbody tr {
        border-color: var(--secondary-gray, #EAEAEA);
        border-width: 1px;
    }
    .seiger-subs-table tbody tr:last-child {
        border-bottom: 1px solid var(--secondary-gray, #EAEAEA);
    }
    .seiger-subs-table tbody tr td:first-child {
        padding-left: 43px;
    }
    .seiger-subs-table tbody tr td:last-child {
        padding-right: 43px;
    }
    .seiger-subs-table {
        background: var(--secondary-white, #FFF);
    }
    .seiger-sub-lock-status, .seiger-sub-remove {
        background: transparent !important;
        font-size: 18px !important;
    }
    .seiger-sort-btn {
        outline: none !important;
    }
    .seiger__bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .seiger__bottom > * {
        flex: 1 100%;
    }
    .seiger__bottom > *:first-of-type,
    .seiger__bottom > *:last-of-type {
        flex: 1 53%;
    }
    .seiger__label {
        display: inline-block;
        color: #63666b;
        font-family: inherit;
        font-size: 14px;
        font-weight: 400;
        line-height: 130%;
        margin-right: 10px;
        white-space: nowrap;
    }
    .seiger-clear-search {
        padding: 5px;
        margin-left: -44px;
        z-index: 10;
        cursor: pointer;
    }
    .seiger-sub-lock-status:hover svg,
    .seiger-sub-remove:hover svg,
    .seiger-clear-search:hover svg{
        transform: scale(1.1);
    }
    /* dropdown */
    .seiger__list {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .dropdown {
        position: relative;
    }
    .dropdown .dropdown__title {
        padding: 8px 12px;
        display: flex;
        align-items: center;
        border-radius: 6px;
        border: 1px solid #cececf;
        background: #fff;
        cursor: pointer;
        outline: none;
    }
    .dropdown .dropdown__title span {
        display: inline-block;
        margin-right: 4px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 400;
        line-height: 120%;
    }
    .dropdown .dropdown__menu {
        visibility: hidden;
        pointer-events: none;
        position: absolute;
        bottom: 0;
        transform: translateY(100%);
        left: 0;
        width: max-content;
        min-width: 100%;
        list-style: none;
        margin: 0;
        padding: 0;
        background: #fff;
        border-radius: 4px;
        border: 1px solid #eaeaea;
        z-index: 999;
    }
    .dropdown.active .dropdown__menu {
        visibility: visible;
        pointer-events: all;
    }
    .dropdown .dropdown__menu-link {
        padding: 8px 12px;
        white-space: nowrap;
        cursor: pointer;
        color: #0d0d0d;
        text-decoration: none;
        display: block;
    }
    .dropdown .dropdown__menu-link:hover {
        text-decoration: none;
        background: #F4F4EF;
    }
    .seiger__module-table {
        width: calc(100% + 81px);
        margin-left: -41px;
    }
    .table-hover tbody tr:hover {
        background: #fff;
    }
    span[data-actual]::before {
        content: attr(data-actual);
    }
    input[type="search"]::-webkit-search-decoration,
    input[type="search"]::-webkit-search-cancel-button,
    input[type="search"]::-webkit-search-results-button,
    input[type="search"]::-webkit-search-results-decoration {
        -webkit-appearance:none;
    }
    /* Hide the AM and PM labels (Chrome) */
    input[type=time]::-webkit-datetime-edit-ampm-field {
        display: none;
    }
    input[type="time"]::-webkit-calendar-picker-indicator {
        background: none;
    }

    input[type=time]::-webkit-clear-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        -ms-appearance:none;
        appearance: none;
        margin: -10px;
    }

    /* Hide the AM and PM labels (Firefox) */
    input[type="time"]::-moz-datetime-increment {
        display: none;
    }

    /* Hide the AM and PM labels (Edge) */
    input[type="time"]::-ms-clear {
        display: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu {
        width: 120px;
        min-width: max-content;
    }
    .bootstrap-datetimepicker-widget .list-unstyled {
        margin: 0;
    }
    .bootstrap-datetimepicker-widget table td span  {
        width: 42px;
        height: 20px;
        line-height: 20px;
    }
    .bootstrap-datetimepicker-widget table td {
        width: 40px;
        line-height: 20px;
        height: 20px;
        text-align:center
    }
    .bootstrap-datetimepicker-widget table .separator {
        width: 10px;
    }
    .bootstrap-datetimepicker-widget a[data-action] {
        padding: initial;
        width: 100%;
        border-radius: 2px;
        padding: 0;
    }
    @media screen and (max-width: 768px) {
        .seiger__bottom {
            flex-wrap: wrap;
        }
    }
</style>
