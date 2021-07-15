<style>
    
    .home-button{padding-top:0 !important;text-align:center;}
    .add-970-90 img{width: 970px; height: 90px;}
    .add-300-600 img{width: 300px; height: 600px;}
    .add-320-100 img{width: 320px; height: 100px;}
    .add-300-250 img{width: 300px; height: 250px;}
    @media (max-width: 767px) { /* in xs device */
      .add-970-90,.add-300-600,.add-320-100,.add-300-250 {
        text-align: center !important;
      } 
      .add-970-90 img,.add-300-600 img,.add-320-100 img,.add-300-250 img{
        margin: 15px 0 !important;
      } 
      .footer-copyright{border-top:none !important;margin-top:20px;}
    }
    @media (min-width: 768px) and (max-width: 991px) { /* in sm device */
      .add-970-90,.add-300-600,.add-320-100,.add-300-250 {
        text-align: center !important;
      } 
      .add-970-90 img,.add-300-600 img,.add-320-100 img,.add-300-250 img{
        margin: 15px 0; !important;
      } 
      .footer-copyright{border-top:none !important;margin-top:20px;}
    }
    .cookiealert{background: #000;padding: 15px 0;opacity: .7;position: fixed;bottom:0;left: 0;z-index: 99999;width: 100%;}
    .add-300-600 img,.add-300-250 img
    {
        border-radius: 15px;
        -moz-border-radius: 15px;
        -webkit-border-radius: 15px;
    } 
    .form_holder {
        display: table;
        margin: 10px 0 3px 35px;
        font-size: 16px;
        background: none !important;
    }
    .bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;}
    #website_name {
        background: #fff none repeat scroll 0 0;
        opacity: .8;
        border: 1px solid #ccc;
        border-right: none;
        border-radius: 50px 0 0 50px;
        display: inline-block;
        float: left;
        font-size: 16px;
        font-weight: 500;
        height: 44px;
        padding-left: 6%;
        text-align: center;
        transition: all 0.3s ease 0s;
        width: 400px;
        color: black !important;
    }

    #website_name:focus{outline: 0 !important;}

    #submit {
        background: #0b72e6 none repeat scroll 0 0;
        opacity: .9;
        border: medium none;
        border-radius: 0 50px 50px 0;
        display: inline-block;
        font-size: 20px;
        font-weight: 400;
        height: 44px;
        line-height: 50px;
        padding-top: 10px;
        transition: all 0.3s ease 0s;
        width: 70px;
        cursor: pointer;
        color: #fff;
    }
    #submit .fa-2x {
        font-size: 1.3em;
        position: relative;
        top: -11px;
    }

    .swal-button.swal-button--confirm {
        box-shadow: 0 2px 6px #acb5f6;
        background-color: #6777ef;
    }
    .swal-button {
        border-radius: 3px;
        font-size: 16px;
    }
    .swal-footer {
        text-align: center;
    }

    @media screen and (max-width: 640px) {
        #website_name {
            width: 200px;
            font-size: 8px;
            margin-left: -66px;
        }

        .form_holder {
            margin-left: 150px;
            /*margin-top: -20px;*/
        }
    }    

    @media only screen and (max-width: 400px) {
        #website_name {
            width: 170px;
            font-size: 8px;
            margin-left: -145px;
        }

        .form_holder {
            margin-left: 200px;
            /*margin-top: -20px;*/
        }
    }


    /* 3.5 Card */
    .card {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
      background-color: #fff;
      border-radius: 3px;
      border: none;
      position: relative;
      margin-bottom: 30px; }
      .card .card-header, .card .card-body, .card .card-footer {
        background-color: transparent;
        padding: 20px 25px; }
      .card .navbar {
        position: static; }
      .card .card-body {
        padding-top: 20px;
        padding-bottom: 20px; }
        .card .card-body .section-title {
          margin: 30px 0 10px 0;
          font-size: 16px; }
          .card .card-body .section-title:before {
            margin-top: 8px; }
        .card .card-body .section-title + .section-lead {
          margin-top: -5px; }
        .card .card-body p {
          font-weight: 500; }
      .card .card-header {
        border-bottom-color: #f9f9f9;
        line-height: 30px;
        -ms-grid-row-align: center;
        align-self: center;
        width: 100%;
        min-height: 70px;
        padding: 15px 25px;
        display: flex;
        align-items: center; }
        .card .card-header .btn {
          margin-top: 1px;
          padding: 2px 15px; }
          .card .card-header .btn:not(.note-btn) {
            border-radius: 30px; }
          .card .card-header .btn:hover {
            box-shadow: none; }
        .card .card-header .form-control {
          height: 31px;
          font-size: 13px;
          border-radius: 30px; }
          .card .card-header .form-control + .input-group-btn .btn {
            margin-top: -1px; }
        .card .card-header h4 {
          font-size: 16px;
          line-height: 28px;
          color: #6777ef;
          padding-right: 10px;
          margin-bottom: 0; }
          .card .card-header h4 + .card-header-action,
          .card .card-header h4 + .card-header-form {
            margin-left: auto; }
            .card .card-header h4 + .card-header-action .btn,
            .card .card-header h4 + .card-header-form .btn {
              font-size: 12px;
              border-radius: 30px !important;
              padding-left: 13px !important;
              padding-right: 13px !important; }
              .card .card-header h4 + .card-header-action .btn.active,
              .card .card-header h4 + .card-header-form .btn.active {
                box-shadow: 0 2px 6px #acb5f6;
                background-color: #6777ef;
                color: #fff; }
            .card .card-header h4 + .card-header-action .dropdown,
            .card .card-header h4 + .card-header-form .dropdown {
              display: inline; }
            .card .card-header h4 + .card-header-action .btn-group .btn,
            .card .card-header h4 + .card-header-form .btn-group .btn {
              border-radius: 0 !important; }
            .card .card-header h4 + .card-header-action .btn-group .btn:first-child,
            .card .card-header h4 + .card-header-form .btn-group .btn:first-child {
              border-radius: 30px 0 0 30px !important; }
            .card .card-header h4 + .card-header-action .btn-group .btn:last-child,
            .card .card-header h4 + .card-header-form .btn-group .btn:last-child {
              border-radius: 0 30px 30px 0 !important; }
            .card .card-header h4 + .card-header-action .input-group .form-control,
            .card .card-header h4 + .card-header-form .input-group .form-control {
              border-radius: 30px 0 0 30px !important; }
              .card .card-header h4 + .card-header-action .input-group .form-control + .input-group-btn .btn,
              .card .card-header h4 + .card-header-form .input-group .form-control + .input-group-btn .btn {
                border-radius: 0 30px 30px 0 !important; }
            .card .card-header h4 + .card-header-action .input-group .input-group-btn + .form-control,
            .card .card-header h4 + .card-header-form .input-group .input-group-btn + .form-control {
              border-radius: 0 30px 30px 0 !important; }
            .card .card-header h4 + .card-header-action .input-group .input-group-btn .btn,
            .card .card-header h4 + .card-header-form .input-group .input-group-btn .btn {
              margin-top: -1px;
              border-radius: 30px 0 0 30px !important; }
      .card .card-footer {
        background-color: transparent;
        border: none; }
      .card.card-mt {
        margin-top: 30px; }
      .card.card-progress:after {
        content: ' ';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.5);
        z-index: 99;
        z-index: 99; }
      .card.card-progress .card-progress-dismiss {
        position: absolute;
        top: 66%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        z-index: 999;
        color: #fff !important;
        padding: 5px 13px; }
      .card.card-progress.remove-spinner .card-progress-dismiss {
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%); }
      .card.card-progress:not(.remove-spinner):after {
        background-image: url("data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJsb2FkZXItMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQogd2lkdGg9IjQwcHgiIGhlaWdodD0iNDBweCIgdmlld0JveD0iMCAwIDUwIDUwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MCA1MDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHBhdGggZmlsbD0iIzAwMCIgZD0iTTQzLjkzNSwyNS4xNDVjMC0xMC4zMTgtOC4zNjQtMTguNjgzLTE4LjY4My0xOC42ODNjLTEwLjMxOCwwLTE4LjY4Myw4LjM2NS0xOC42ODMsMTguNjgzaDQuMDY4YzAtOC4wNzEsNi41NDMtMTQuNjE1LDE0LjYxNS0xNC42MTVjOC4wNzIsMCwxNC42MTUsNi41NDMsMTQuNjE1LDE0LjYxNUg0My45MzV6Ij4NCjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZVR5cGU9InhtbCINCiAgYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIg0KICB0eXBlPSJyb3RhdGUiDQogIGZyb209IjAgMjUgMjUiDQogIHRvPSIzNjAgMjUgMjUiDQogIGR1cj0iMC42cyINCiAgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz4NCjwvcGF0aD4NCjwvc3ZnPg0K");
        background-size: 80px;
        background-repeat: no-repeat;
        background-position: center; }
      .card.card-primary {
        border-top: 2px solid #6777ef; }
      .card.card-secondary {
        border-top: 2px solid #34395e; }
      .card.card-success {
        border-top: 2px solid #63ed7a; }
      .card.card-danger {
        border-top: 2px solid #fc544b; }
      .card.card-warning {
        border-top: 2px solid #ffa426; }
      .card.card-info {
        border-top: 2px solid #3abaf4; }
      .card.card-dark {
        border-top: 2px solid #191d21; }
      .card.card-hero .card-header {
        padding: 40px;
        background-image: linear-gradient(to bottom, #6777ef, #95a0f4);
        color: #fff;
        overflow: hidden;
        height: auto;
        min-height: auto;
        display: block; }
        .card.card-hero .card-header h4 {
          font-size: 40px;
          line-height: 1;
          color: #fff; }
        .card.card-hero .card-header .card-description {
          margin-top: 5px;
          font-size: 16px; }
        .card.card-hero .card-header .card-icon {
          float: right;
          color: #8c98f3;
          margin: -60px; }
          .card.card-hero .card-header .card-icon .ion, .card.card-hero .card-header .card-icon .fas, .card.card-hero .card-header .card-icon .far, .card.card-hero .card-header .card-icon .fab, .card.card-hero .card-header .card-icon .fal {
            font-size: 140px; }
      .card.card-statistic-1 .card-header, .card.card-statistic-2 .card-header {
        border-color: transparent;
        padding-bottom: 0;
        height: auto;
        min-height: auto;
        display: block; }
      .card.card-statistic-1 .card-header h4,
      .card.card-statistic-2 .card-header h4 {
        line-height: 1.2;
        color: #98a6ad; }
      .card.card-statistic-1 .card-body,
      .card.card-statistic-2 .card-body {
        padding-top: 0; }
      .card.card-statistic-1 .card-body, .card.card-statistic-2 .card-body {
        font-size: 26px;
        font-weight: 700;
        color: #34395e;
        padding-bottom: 0; }
      .card.card-statistic-1, .card.card-statistic-2 {
        display: inline-block;
        width: 100%; }
      .card.card-statistic-1 .card-icon, .card.card-statistic-2 .card-icon {
        width: 80px;
        height: 80px;
        margin: 10px;
        border-radius: 3px;
        line-height: 94px;
        text-align: center;
        float: left;
        margin-right: 15px; }
        .card.card-statistic-1 .card-icon .ion, .card.card-statistic-1 .card-icon .fas, .card.card-statistic-1 .card-icon .far, .card.card-statistic-1 .card-icon .fab, .card.card-statistic-1 .card-icon .fal, .card.card-statistic-2 .card-icon .ion, .card.card-statistic-2 .card-icon .fas, .card.card-statistic-2 .card-icon .far, .card.card-statistic-2 .card-icon .fab, .card.card-statistic-2 .card-icon .fal {
          font-size: 22px;
          color: #fff; }
      .card.card-statistic-1 .card-icon {
        line-height: 90px; }
      .card.card-statistic-2 .card-icon {
        width: 50px;
        height: 50px;
        line-height: 50px;
        font-size: 22px;
        margin: 25px; }
      .card.card-statistic-1 .card-header, .card.card-statistic-2 .card-header {
        padding-bottom: 0;
        padding-top: 25px; }
      .card.card-statistic-2 .card-body {
        padding-top: 20px; }
      .card.card-statistic-2 .card-header + .card-body,
      .card.card-statistic-2 .card-body + .card-header {
        padding-top: 0; }
      .card.card-statistic-1 .card-header h4, .card.card-statistic-2 .card-header h4 {
        font-weight: 600;
        font-size: 13px;
        letter-spacing: .5px; }
      .card.card-statistic-1 .card-header h4 {
        margin-bottom: 0; }
      .card.card-statistic-2 .card-header h4 {
        text-transform: none;
        margin-bottom: 0; }
      .card.card-statistic-1 .card-body {
        font-size: 20px; }
      .card.card-statistic-2 .card-chart {
        padding-top: 20px;
        margin-left: -9px;
        margin-right: -1px;
        margin-bottom: -15px; }
        .card.card-statistic-2 .card-chart canvas {
          height: 90px !important; }
      .card .card-stats {
        width: 100%;
        display: inline-block;
        margin-top: 2px;
        margin-bottom: -6px; }
        .card .card-stats .card-stats-title {
          padding: 15px 25px;
          background-color: #fff;
          font-size: 13px;
          font-weight: 600;
          letter-spacing: .3px; }
        .card .card-stats .card-stats-items {
          display: flex;
          height: 50px;
          align-items: center; }
        .card .card-stats .card-stats-item {
          width: calc(100% / 3);
          text-align: center;
          padding: 5px 20px; }
          .card .card-stats .card-stats-item .card-stats-item-label {
            font-size: 12px;
            letter-spacing: .5px;
            margin-top: 4px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap; }
          .card .card-stats .card-stats-item .card-stats-item-count {
            line-height: 1;
            margin-bottom: 8px;
            font-size: 20px;
            font-weight: 700; }
      .card.card-large-icons {
        display: flex;
        flex-direction: row; }
        .card.card-large-icons .card-icon {
          display: flex;
          align-items: center;
          justify-content: center;
          flex-shrink: 0;
          width: 150px;
          border-radius: 3px 0 0 3px; }
          .card.card-large-icons .card-icon .ion, .card.card-large-icons .card-icon .fas, .card.card-large-icons .card-icon .far, .card.card-large-icons .card-icon .fab, .card.card-large-icons .card-icon .fal {
            font-size: 60px; }
        .card.card-large-icons .card-body {
          padding: 25px 30px; }
          .card.card-large-icons .card-body h4 {
            font-size: 18px; }
          .card.card-large-icons .card-body p {
            opacity: .6;
            font-weight: 500; }
          .card.card-large-icons .card-body a.card-cta {
            text-decoration: none; }
            .card.card-large-icons .card-body a.card-cta i {
              margin-left: 7px; }
      .card.bg-primary, .card.bg-danger, .card.bg-success, .card.bg-info, .card.bg-dark, .card.bg-warning {
        color: #fff; }
      .card.bg-primary .card-header, .card.bg-danger .card-header, .card.bg-success .card-header, .card.bg-info .card-header, .card.bg-dark .card-header, .card.bg-warning .card-header {
        color: #fff;
        opacity: .9; }

    @media (max-width: 575.98px) {
      .card.card-large-icons {
        display: inline-block; }
        .card.card-large-icons .card-icon {
          width: 100%;
          height: 200px; } }

    @media (max-width: 767.98px) {
      .card .card-header {
        height: auto;
        flex-wrap: wrap; }
        .card .card-header h4 + .card-header-action,
        .card .card-header h4 + .card-header-form {
          flex-grow: 0;
          width: 100%;
          margin-top: 10px; } }

    @media (min-width: 768px) and (max-width: 991.98px) {
      .card .card-stats .card-stats-items {
        height: 49px; }
        .card .card-stats .card-stats-items .card-stats-item {
          padding: 5px 7px; }
          .card .card-stats .card-stats-items .card-stats-item .card-stats-item-count {
            font-size: 16px; }
      .card.card-sm-6 .card-chart canvas {
        height: 85px !important; }
      .card.card-hero .card-header {
        padding: 25px; } }

        .blue {
            color: #6777EF !important;
        }

        /* 3.21 Progress Bar */
        .progress-bar {
          background-color: #6777ef; }


        /* 3.8 Modal */
        .modal-header,
        .modal-body,
        .modal-footer {
          padding: 25px; }

        /* .modal-body {
          padding-top: 15px; } */

        .modal-footer {
          padding-top: 15px;
          padding-bottom: 15px; }

        .modal-header {
          border-bottom: none;
          padding-bottom: 5px; }
          
          .modal-header h5 {
            font-size: 18px; }

        .modal-footer {
          border-top: none;
          border-radius: 0 0 3px 3px; }

        .modal-content {
          max-width: 100%;
          border: none;
          box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05); }

        .modal.show .modal-content {
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); }

        .modal-progress .modal-content {
          position: relative; }
          .modal-progress .modal-content:after {
            content: ' ';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            z-index: 999;
            background-image: url("data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJsb2FkZXItMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQogd2lkdGg9IjQwcHgiIGhlaWdodD0iNDBweCIgdmlld0JveD0iMCAwIDUwIDUwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MCA1MDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHBhdGggZmlsbD0iIzAwMCIgZD0iTTQzLjkzNSwyNS4xNDVjMC0xMC4zMTgtOC4zNjQtMTguNjgzLTE4LjY4My0xOC42ODNjLTEwLjMxOCwwLTE4LjY4Myw4LjM2NS0xOC42ODMsMTguNjgzaDQuMDY4YzAtOC4wNzEsNi41NDMtMTQuNjE1LDE0LjYxNS0xNC42MTVjOC4wNzIsMCwxNC42MTUsNi41NDMsMTQuNjE1LDE0LjYxNUg0My45MzV6Ij4NCjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZVR5cGU9InhtbCINCiAgYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIg0KICB0eXBlPSJyb3RhdGUiDQogIGZyb209IjAgMjUgMjUiDQogIHRvPSIzNjAgMjUgMjUiDQogIGR1cj0iMC42cyINCiAgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz4NCjwvcGF0aD4NCjwvc3ZnPg0K");
            background-size: 80px;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 3px; }

        .modal-part {
          display: none; }

          .btn-primary, .btn-primary.disabled {
            box-shadow: 0 2px 6px #acb5f6;
            background-color: #6777ef;
            border-color: #6777ef; }
            .btn-primary:focus, .btn-primary.disabled:focus {
              background-color: #394eea !important; }
              .btn-primary:focus:active, .btn-primary.disabled:focus:active {
                background-color: #394eea !important; }
            .btn-primary:active, .btn-primary:hover, .btn-primary.disabled:active, .btn-primary.disabled:hover {
              background-color: #394eea !important; }
              #completed_function_str { font-size:14px !important; }
              .bg-whitesmoke {
                  background-color: #f7f9f9 !important;
              }

              .bg-primary {
                background-color: #6777ef !important; }

              .bg-secondary {
                background-color: #cdd3d8 !important; }

              .bg-success {
                background-color: #63ed7a !important; }

              .bg-info {
                background-color: #3abaf4 !important; }

              .bg-warning {
                background-color: #ffa426 !important; }

              .bg-danger {
                background-color: #fc544b !important; }

              .bg-light {
                background-color: #e3eaef !important; }

              .bg-dark {
                background-color: #191d21 !important; }
            .text-primary, .text-primary-all *, .text-primary-all *:before, .text-primary-all *:after {
              color: #6777ef !important; }

            .text-secondary, .text-secondary-all *, .text-secondary-all *:before, .text-secondary-all *:after {
              color: #cdd3d8 !important; }

            .text-success, .text-success-all *, .text-success-all *:before, .text-success-all *:after {
              color: #63ed7a !important; }

            .text-info, .text-info-all *, .text-info-all *:before, .text-info-all *:after {
              color: #3abaf4 !important; }

            .text-warning, .text-warning-all *, .text-warning-all *:before, .text-warning-all *:after {
              color: #ffa426 !important; }

            .text-danger, .text-danger-all *, .text-danger-all *:before, .text-danger-all *:after {
              color: #fc544b !important; }

            .text-light, .text-light-all *, .text-light-all *:before, .text-light-all *:after {
              color: #e3eaef !important; }

            .text-white, .text-white-all *, .text-white-all *:before, .text-white-all *:after {
              color: #ffffff !important; }

            .text-dark, .text-dark-all *, .text-dark-all *:before, .text-dark-all *:after {
              color: #191d21 !important; }
              .modal-header, .modal-body, .modal-footer {
                  padding: 25px !important;
              }
</style>