<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NFC Tracker | Print</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic);

        html {
            font-family: sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            margin: 0;
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
            display: block;
        }

        audio, canvas, progress, video {
            display: inline-block;
            vertical-align: baseline;
        }

            audio:not([controls]) {
                display: none;
                height: 0;
            }

        [hidden], template {
            display: none;
        }

        a {
            background-color: transparent;
        }

            a:active, a:hover {
                outline: 0;
            }

        abbr[title] {
            border-bottom: 1px dotted;
        }

        b, strong {
            font-weight: 700;
        }

        dfn {
            font-style: italic;
        }

        h1 {
            margin: .67em 0;
            font-size: 2em;
        }

        mark {
            color: #000;
            background: #ff0;
        }

        small {
            font-size: 80%;
        }

        sub, sup {
            position: relative;
            font-size: 75%;
            line-height: 0;
            vertical-align: baseline;
        }

        sup {
            top: -.5em;
        }

        sub {
            bottom: -.25em;
        }

        img {
            border: 0;
        }

        svg:not(:root) {
            overflow: hidden;
        }

        figure {
            margin: 1em 40px;
        }

        hr {
            height: 0;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
        }

        pre {
            overflow: auto;
        }

        code, kbd, pre, samp {
            font-family: monospace,monospace;
            font-size: 1em;
        }

        button, input, optgroup, select, textarea {
            margin: 0;
            font: inherit;
            color: inherit;
        }

        button {
            overflow: visible;
        }

        button, select {
            text-transform: none;
        }

        button, html input[type=button], input[type=reset], input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer;
        }

            button[disabled], html input[disabled] {
                cursor: default;
            }

            button::-moz-focus-inner, input::-moz-focus-inner {
                padding: 0;
                border: 0;
            }

        input {
            line-height: normal;
        }

            input[type=checkbox], input[type=radio] {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 0;
            }

            input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button {
                height: auto;
            }

            input[type=search] {
                -webkit-box-sizing: content-box;
                -moz-box-sizing: content-box;
                box-sizing: content-box;
                -webkit-appearance: textfield;
            }

                input[type=search]::-webkit-search-cancel-button, input[type=search]::-webkit-search-decoration {
                    -webkit-appearance: none;
                }

        fieldset {
            padding: .35em .625em .75em;
            margin: 0 2px;
            border: 1px solid silver;
        }

        legend {
            padding: 0;
            border: 0;
        }

        textarea {
            overflow: auto;
        }

        optgroup {
            font-weight: 700;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        td, th {
            padding: 0;
        }
        /*! Source: https://github.com/h5bp/html5-boilerplate/blob/master/src/css/main.css */

        @media print {
            *, :after, :before {
                color: #000 !important;
                text-shadow: none !important;
                background: 0 0 !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
            }

            a, a:visited {
                text-decoration: underline;
            }

                a[href]:after {
                    content: " (" attr(href) ")";
                }

            abbr[title]:after {
                content: " (" attr(title) ")";
            }

            a[href^="javascript:"]:after, a[href^="#"]:after {
                content: "";
            }

            blockquote, pre {
                border: 1px solid #999;
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            img, tr {
                page-break-inside: avoid;
            }

            img {
                max-width: 100% !important;
            }

            h2, h3, p {
                orphans: 3;
                widows: 3;
            }

            h2, h3 {
                page-break-after: avoid;
            }

            .navbar {
                display: none;
            }

            .btn > .caret, .dropup > .btn > .caret {
                border-top-color: #000 !important;
            }

            .label {
                border: 1px solid #000;
            }

            .table {
                border-collapse: collapse !important;
            }

                .table td, .table th {
                    background-color: #fff !important;
                }

            .table-bordered td, .table-bordered th {
                border: 1px solid #ddd !important;
            }
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        :after, :before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        html {
            font-size: 10px;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
        }

        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }

        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }

        a {
            color: #337ab7;
            text-decoration: none;
        }

            a:focus, a:hover {
                color: #23527c;
                text-decoration: underline;
            }

            a:focus {
                outline: 5px auto -webkit-focus-ring-color;
                outline-offset: -2px;
            }

        figure {
            margin: 0;
        }

        img {
            vertical-align: middle;
        }

        .carousel-inner > .item > a > img, .carousel-inner > .item > img, .img-responsive, .thumbnail a > img, .thumbnail > img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .img-rounded {
            border-radius: 6px;
        }

        .img-thumbnail {
            display: inline-block;
            max-width: 100%;
            height: auto;
            padding: 4px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
        }

        .img-circle {
            border-radius: 50%;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #eee;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            border: 0;
        }

        .sr-only-focusable:active, .sr-only-focusable:focus {
            position: static;
            width: auto;
            height: auto;
            margin: 0;
            overflow: visible;
            clip: auto;
        }

        [role=button] {
            cursor: pointer;
        }

        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
        }

            .h1 .small, .h1 small, .h2 .small, .h2 small, .h3 .small, .h3 small, .h4 .small, .h4 small, .h5 .small, .h5 small, .h6 .small, .h6 small, h1 .small, h1 small, h2 .small, h2 small, h3 .small, h3 small, h4 .small, h4 small, h5 .small, h5 small, h6 .small, h6 small {
                font-weight: 400;
                line-height: 1;
                color: #777;
            }

        .h1, .h2, .h3, h1, h2, h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

            .h1 .small, .h1 small, .h2 .small, .h2 small, .h3 .small, .h3 small, h1 .small, h1 small, h2 .small, h2 small, h3 .small, h3 small {
                font-size: 65%;
            }

        .h4, .h5, .h6, h4, h5, h6 {
            margin-top: 10px;
            margin-bottom: 10px;
        }

            .h4 .small, .h4 small, .h5 .small, .h5 small, .h6 .small, .h6 small, h4 .small, h4 small, h5 .small, h5 small, h6 .small, h6 small {
                font-size: 75%;
            }

        .h1, h1 {
            font-size: 36px;
        }

        .h2, h2 {
            font-size: 30px;
        }

        .h3, h3 {
            font-size: 24px;
        }

        .h4, h4 {
            font-size: 18px;
        }

        .h5, h5 {
            font-size: 14px;
        }

        .h6, h6 {
            font-size: 12px;
        }

        p {
            margin: 0 0 10px;
        }

        .lead {
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.4;

        }

        @media (min-width:768px) {
            .lead {
                font-size: 21px;
				font-size:18px;
            }
        }

        .small, small {
            font-size: 85%;
        }

        .mark, mark {
            padding: .2em;
            background-color: #fcf8e3;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .text-lowercase {
            text-transform: lowercase;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        .text-muted {
            color: #777;
        }

        .text-primary {
            color: #337ab7;
        }

        a.text-primary:focus, a.text-primary:hover {
            color: #286090;
        }

        .text-success {
            color: #3c763d;
        }

        a.text-success:focus, a.text-success:hover {
            color: #2b542c;
        }

        .text-info {
            color: #31708f;
        }

        a.text-info:focus, a.text-info:hover {
            color: #245269;
        }

        .text-warning {
            color: #8a6d3b;
        }

        a.text-warning:focus, a.text-warning:hover {
            color: #66512c;
        }

        .text-danger {
            color: #a94442;
        }

        a.text-danger:focus, a.text-danger:hover {
            color: #843534;
        }

        .bg-primary {
            color: #fff;
            background-color: #337ab7;
        }

        a.bg-primary:focus, a.bg-primary:hover {
            background-color: #286090;
        }

        .bg-success {
            background-color: #dff0d8;
        }

        a.bg-success:focus, a.bg-success:hover {
            background-color: #c1e2b3;
        }

        .bg-info {
            background-color: #d9edf7;
        }

        a.bg-info:focus, a.bg-info:hover {
            background-color: #afd9ee;
        }

        .bg-warning {
            background-color: #fcf8e3;
        }

        a.bg-warning:focus, a.bg-warning:hover {
            background-color: #f7ecb5;
        }

        .bg-danger {
            background-color: #f2dede;
        }

        a.bg-danger:focus, a.bg-danger:hover {
            background-color: #e4b9b9;
        }

        .page-header {
            padding-bottom: 9px;
            margin: 40px 0 20px;
            border-bottom: 1px solid #eee;
        }

        ol, ul {
            margin-top: 0;
            margin-bottom: 10px;
        }

            ol ol, ol ul, ul ol, ul ul {
                margin-bottom: 0;
            }

        .list-unstyled {
            padding-left: 0;
            list-style: none;
        }

        .list-inline {
            padding-left: 0;
            margin-left: -5px;
            list-style: none;
        }

            .list-inline > li {
                display: inline-block;
                padding-right: 5px;
                padding-left: 5px;
            }

        dl {
            margin-top: 0;
            margin-bottom: 20px;
        }

        dd, dt {
            line-height: 1.42857143;
        }

        dt {
            font-weight: 700;
        }

        dd {
            margin-left: 0;
        }

        @media (min-width:768px) {
            .dl-horizontal dt {
                float: left;
                width: 160px;
                overflow: hidden;
                clear: left;
                text-align: right;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .dl-horizontal dd {
                margin-left: 180px;
            }
        }

        abbr[data-original-title], abbr[title] {
            cursor: help;
            border-bottom: 1px dotted #777;
        }

        .initialism {
            font-size: 90%;
            text-transform: uppercase;
        }

        address {
            margin-bottom: 20px;
            font-style: normal;
            line-height: 1.42857143;
        }

        code, kbd, pre, samp {
            font-family: Menlo,Monaco,Consolas,"Courier New",monospace;
        }

        code {
            padding: 2px 4px;
            font-size: 90%;
            color: #c7254e;
            background-color: #f9f2f4;
            border-radius: 4px;
        }

        kbd {
            padding: 2px 4px;
            font-size: 90%;
            color: #fff;
            background-color: #333;
            border-radius: 3px;
            -webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
            box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
        }

            kbd kbd {
                padding: 0;
                font-size: 100%;
                font-weight: 700;
                -webkit-box-shadow: none;
                box-shadow: none;
            }

        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

            pre code {
                padding: 0;
                font-size: inherit;
                color: inherit;
                white-space: pre-wrap;
                background-color: transparent;
                border-radius: 0;
            }

        .pre-scrollable {
            max-height: 340px;
            overflow-y: scroll;
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width:768px) {
            .container {
                width: 750px;
            }
        }

        @media (min-width:992px) {
            .container {
                width: 970px;
            }
        }

        @media (min-width:1200px) {
            .container {
                width: 1170px;
            }
        }

        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            float: left;
        }

        .col-xs-12 {
            width: 100%;
        }

        .col-xs-11 {
            width: 91.66666667%;
        }

        .col-xs-10 {
            width: 83.33333333%;
        }

        .col-xs-9 {
            width: 75%;
        }

        .col-xs-8 {
            width: 66.66666667%;
        }

        .col-xs-7 {
            width: 58.33333333%;
        }

        .col-xs-6 {
            width: 50%;
        }

        .col-xs-5 {
            width: 41.66666667%;
        }

        .col-xs-4 {
            width: 33.33333333%;
        }

        .col-xs-3 {
            width: 25%;
        }

        .col-xs-2 {
            width: 16.66666667%;
        }

        .col-xs-1 {
            width: 8.33333333%;
        }

        @media (min-width:768px) {
            .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9 {
                float: left;
            }

            .col-sm-12 {
                width: 100%;
            }

            .col-sm-11 {
                width: 91.66666667%;
            }

            .col-sm-10 {
                width: 83.33333333%;
            }

            .col-sm-9 {
                width: 75%;
            }

            .col-sm-8 {
                width: 66.66666667%;
            }

            .col-sm-7 {
                width: 58.33333333%;
            }

            .col-sm-6 {
                width: 50%;
            }

            .col-sm-5 {
                width: 41.66666667%;
            }

            .col-sm-4 {
                width: 33.33333333%;
            }

            .col-sm-3 {
                width: 25%;
            }

            .col-sm-2 {
                width: 16.66666667%;
            }

            .col-sm-1 {
                width: 8.33333333%;
            }
        }

        @media (min-width:992px) {
            .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
                float: left;
            }

            .col-md-12 {
                width: 100%;
            }

            .col-md-11 {
                width: 91.66666667%;
            }

            .col-md-10 {
                width: 83.33333333%;
            }

            .col-md-9 {
                width: 75%;
            }

            .col-md-8 {
                width: 66.66666667%;
            }

            .col-md-7 {
                width: 58.33333333%;
            }

            .col-md-6 {
                width: 50%;
            }

            .col-md-5 {
                width: 41.66666667%;
            }

            .col-md-4 {
                width: 33.33333333%;
            }

            .col-md-3 {
                width: 25%;
            }

            .col-md-2 {
                width: 16.66666667%;
            }

            .col-md-1 {
                width: 8.33333333%;
            }
        }

        @media (min-width:1200px) {
            .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9 {
                float: left;
            }

            .col-lg-12 {
                width: 100%;
            }

            .col-lg-11 {
                width: 91.66666667%;
            }

            .col-lg-10 {
                width: 83.33333333%;
            }

            .col-lg-9 {
                width: 75%;
            }

            .col-lg-8 {
                width: 66.66666667%;
            }

            .col-lg-7 {
                width: 58.33333333%;
            }

            .col-lg-6 {
                width: 50%;
            }

            .col-lg-5 {
                width: 41.66666667%;
            }

            .col-lg-4 {
                width: 33.33333333%;
            }

            .col-lg-3 {
                width: 25%;
            }

            .col-lg-2 {
                width: 16.66666667%;
            }

            .col-lg-1 {
                width: 8.33333333%;
            }
        }

        table {
            background-color: transparent;
        }

        caption {
            padding-top: 8px;
            padding-bottom: 8px;
            color: #777;
            text-align: left;
        }

        th {
            text-align: left;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

            .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #ddd;
            }

            .table > thead > tr > th {
                vertical-align: bottom;
                border-bottom: 2px solid #ddd;
            }

            .table > caption + thead > tr:first-child > td, .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table > thead:first-child > tr:first-child > th {
                border-top: 0;
            }

            .table > tbody + tbody {
                border-top: 2px solid #ddd;
            }

            .table .table {
                background-color: #fff;
            }

        .table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th {
            padding: 5px;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

            .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
                border: 1px solid #ddd;
            }

            .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
                border-bottom-width: 2px;
            }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-hover > tbody > tr:hover {
            background-color: #f5f5f5;
        }

        table col[class*=col-] {
            position: static;
            display: table-column;
            float: none;
        }

        table td[class*=col-], table th[class*=col-] {
            position: static;
            display: table-cell;
            float: none;
        }

        .table > tbody > tr.active > td, .table > tbody > tr.active > th, .table > tbody > tr > td.active, .table > tbody > tr > th.active, .table > tfoot > tr.active > td, .table > tfoot > tr.active > th, .table > tfoot > tr > td.active, .table > tfoot > tr > th.active, .table > thead > tr.active > td, .table > thead > tr.active > th, .table > thead > tr > td.active, .table > thead > tr > th.active {
            background-color: #f5f5f5;
        }

        .table-hover > tbody > tr.active:hover > td, .table-hover > tbody > tr.active:hover > th, .table-hover > tbody > tr:hover > .active, .table-hover > tbody > tr > td.active:hover, .table-hover > tbody > tr > th.active:hover {
            background-color: #e8e8e8;
        }

        .table > tbody > tr.success > td, .table > tbody > tr.success > th, .table > tbody > tr > td.success, .table > tbody > tr > th.success, .table > tfoot > tr.success > td, .table > tfoot > tr.success > th, .table > tfoot > tr > td.success, .table > tfoot > tr > th.success, .table > thead > tr.success > td, .table > thead > tr.success > th, .table > thead > tr > td.success, .table > thead > tr > th.success {
            background-color: #dff0d8;
        }

        .table-hover > tbody > tr.success:hover > td, .table-hover > tbody > tr.success:hover > th, .table-hover > tbody > tr:hover > .success, .table-hover > tbody > tr > td.success:hover, .table-hover > tbody > tr > th.success:hover {
            background-color: #d0e9c6;
        }

        .table > tbody > tr.info > td, .table > tbody > tr.info > th, .table > tbody > tr > td.info, .table > tbody > tr > th.info, .table > tfoot > tr.info > td, .table > tfoot > tr.info > th, .table > tfoot > tr > td.info, .table > tfoot > tr > th.info, .table > thead > tr.info > td, .table > thead > tr.info > th, .table > thead > tr > td.info, .table > thead > tr > th.info {
            background-color: #d9edf7;
        }

        .table-hover > tbody > tr.info:hover > td, .table-hover > tbody > tr.info:hover > th, .table-hover > tbody > tr:hover > .info, .table-hover > tbody > tr > td.info:hover, .table-hover > tbody > tr > th.info:hover {
            background-color: #c4e3f3;
        }

        .table > tbody > tr.warning > td, .table > tbody > tr.warning > th, .table > tbody > tr > td.warning, .table > tbody > tr > th.warning, .table > tfoot > tr.warning > td, .table > tfoot > tr.warning > th, .table > tfoot > tr > td.warning, .table > tfoot > tr > th.warning, .table > thead > tr.warning > td, .table > thead > tr.warning > th, .table > thead > tr > td.warning, .table > thead > tr > th.warning {
            background-color: #fcf8e3;
        }

        .table-hover > tbody > tr.warning:hover > td, .table-hover > tbody > tr.warning:hover > th, .table-hover > tbody > tr:hover > .warning, .table-hover > tbody > tr > td.warning:hover, .table-hover > tbody > tr > th.warning:hover {
            background-color: #faf2cc;
        }

        .table > tbody > tr.danger > td, .table > tbody > tr.danger > th, .table > tbody > tr > td.danger, .table > tbody > tr > th.danger, .table > tfoot > tr.danger > td, .table > tfoot > tr.danger > th, .table > tfoot > tr > td.danger, .table > tfoot > tr > th.danger, .table > thead > tr.danger > td, .table > thead > tr.danger > th, .table > thead > tr > td.danger, .table > thead > tr > th.danger {
            background-color: #f2dede;
        }

        .table-hover > tbody > tr.danger:hover > td, .table-hover > tbody > tr.danger:hover > th, .table-hover > tbody > tr:hover > .danger, .table-hover > tbody > tr > td.danger:hover, .table-hover > tbody > tr > th.danger:hover {
            background-color: #ebcccc;
        }

        .table-responsive {
            min-height: .01%;
            overflow-x: auto;
        }

        @media screen and (max-width:767px) {
            .table-responsive {
                width: 100%;
                margin-bottom: 15px;
                overflow-y: hidden;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                border: 1px solid #ddd;
            }

                .table-responsive > .table {
                    margin-bottom: 0;
                }

                    .table-responsive > .table > tbody > tr > td, .table-responsive > .table > tbody > tr > th, .table-responsive > .table > tfoot > tr > td, .table-responsive > .table > tfoot > tr > th, .table-responsive > .table > thead > tr > td, .table-responsive > .table > thead > tr > th {
                        white-space: nowrap;
                    }

                .table-responsive > .table-bordered {
                    border: 0;
                }

                    .table-responsive > .table-bordered > tbody > tr > td:first-child, .table-responsive > .table-bordered > tbody > tr > th:first-child, .table-responsive > .table-bordered > tfoot > tr > td:first-child, .table-responsive > .table-bordered > tfoot > tr > th:first-child, .table-responsive > .table-bordered > thead > tr > td:first-child, .table-responsive > .table-bordered > thead > tr > th:first-child {
                        border-left: 0;
                    }

                    .table-responsive > .table-bordered > tbody > tr > td:last-child, .table-responsive > .table-bordered > tbody > tr > th:last-child, .table-responsive > .table-bordered > tfoot > tr > td:last-child, .table-responsive > .table-bordered > tfoot > tr > th:last-child, .table-responsive > .table-bordered > thead > tr > td:last-child, .table-responsive > .table-bordered > thead > tr > th:last-child {
                        border-right: 0;
                    }

                    .table-responsive > .table-bordered > tbody > tr:last-child > td, .table-responsive > .table-bordered > tbody > tr:last-child > th, .table-responsive > .table-bordered > tfoot > tr:last-child > td, .table-responsive > .table-bordered > tfoot > tr:last-child > th {
                        border-bottom: 0;
                    }
        }

        fieldset {
            min-width: 0;
            padding: 0;
            margin: 0;
            border: 0;
        }

        legend {
            display: block;
            width: 100%;
            padding: 0;
            margin-bottom: 20px;
            font-size: 21px;
            line-height: inherit;
            color: #333;
            border: 0;
            border-bottom: 1px solid #e5e5e5;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .breadcrumb {
            padding: 8px 15px;
            margin-bottom: 20px;
            list-style: none;
            background-color: #f5f5f5;
            border-radius: 4px;
        }

            .breadcrumb > li {
                display: inline-block;
            }

                .breadcrumb > li + li:before {
                    padding: 0 5px;
                    color: #ccc;
                    content: "/\00a0";
                }

            .breadcrumb > .active {
                color: #777;
            }

        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }

            .pagination > li {
                display: inline;
            }

                .pagination > li > a, .pagination > li > span {
                    position: relative;
                    float: left;
                    padding: 6px 12px;
                    margin-left: -1px;
                    line-height: 1.42857143;
                    color: #337ab7;
                    text-decoration: none;
                    background-color: #fff;
                    border: 1px solid #ddd;
                }

                .pagination > li:first-child > a, .pagination > li:first-child > span {
                    margin-left: 0;
                    border-top-left-radius: 4px;
                    border-bottom-left-radius: 4px;
                }

                .pagination > li:last-child > a, .pagination > li:last-child > span {
                    border-top-right-radius: 4px;
                    border-bottom-right-radius: 4px;
                }

                .pagination > li > a:focus, .pagination > li > a:hover, .pagination > li > span:focus, .pagination > li > span:hover {
                    z-index: 2;
                    color: #23527c;
                    background-color: #eee;
                    border-color: #ddd;
                }

            .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
                z-index: 3;
                color: #fff;
                cursor: default;
                background-color: #337ab7;
                border-color: #337ab7;
            }

            .pagination > .disabled > a, .pagination > .disabled > a:focus, .pagination > .disabled > a:hover, .pagination > .disabled > span, .pagination > .disabled > span:focus, .pagination > .disabled > span:hover {
                color: #777;
                cursor: not-allowed;
                background-color: #fff;
                border-color: #ddd;
            }

        .pagination-lg > li > a, .pagination-lg > li > span {
            padding: 10px 16px;
            font-size: 18px;
            line-height: 1.3333333;
        }

        .pagination-lg > li:first-child > a, .pagination-lg > li:first-child > span {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .pagination-lg > li:last-child > a, .pagination-lg > li:last-child > span {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .pagination-sm > li > a, .pagination-sm > li > span {
            padding: 5px 10px;
            font-size: 12px;
            line-height: 1.5;
        }

        .pagination-sm > li:first-child > a, .pagination-sm > li:first-child > span {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .pagination-sm > li:last-child > a, .pagination-sm > li:last-child > span {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        .pager {
            padding-left: 0;
            margin: 20px 0;
            text-align: center;
            list-style: none;
        }

            .pager li {
                display: inline;
            }

                .pager li > a, .pager li > span {
                    display: inline-block;
                    padding: 5px 14px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 15px;
                }

                    .pager li > a:focus, .pager li > a:hover {
                        text-decoration: none;
                        background-color: #eee;
                    }

            .pager .next > a, .pager .next > span {
                float: right;
            }

            .pager .previous > a, .pager .previous > span {
                float: left;
            }

            .pager .disabled > a, .pager .disabled > a:focus, .pager .disabled > a:hover, .pager .disabled > span {
                color: #777;
                cursor: not-allowed;
                background-color: #fff;
            }

        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        a.label:focus, a.label:hover {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .label:empty {
            display: none;
        }

        .btn .label {
            position: relative;
            top: -1px;
        }

        .label-default {
            background-color: #777;
        }

            .label-default[href]:focus, .label-default[href]:hover {
                background-color: #5e5e5e;
            }

        .label-primary {
            background-color: #337ab7;
        }

            .label-primary[href]:focus, .label-primary[href]:hover {
                background-color: #286090;
            }

        .label-success {
            background-color: #5cb85c;
        }

            .label-success[href]:focus, .label-success[href]:hover {
                background-color: #449d44;
            }

        .label-info {
            background-color: #5bc0de;
        }

            .label-info[href]:focus, .label-info[href]:hover {
                background-color: #31b0d5;
            }

        .label-warning {
            background-color: #f0ad4e;
        }

            .label-warning[href]:focus, .label-warning[href]:hover {
                background-color: #ec971f;
            }

        .label-danger {
            background-color: #d9534f;
        }

            .label-danger[href]:focus, .label-danger[href]:hover {
                background-color: #c9302c;
            }

        .badge {
            display: inline-block;
            min-width: 10px;
            padding: 3px 7px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            background-color: #777;
            border-radius: 10px;
        }

            .badge:empty {
                display: none;
            }

        .btn .badge {
            position: relative;
            top: -1px;
        }

        .btn-group-xs > .btn .badge, .btn-xs .badge {
            top: 0;
            padding: 1px 5px;
        }

        a.badge:focus, a.badge:hover {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .list-group-item.active > .badge, .nav-pills > .active > a > .badge {
            color: #337ab7;
            background-color: #fff;
        }

        .list-group-item > .badge {
            float: right;
        }

            .list-group-item > .badge + .badge {
                margin-right: 5px;
            }

        .nav-pills > li > a > .badge {
            margin-left: 3px;
        }

        .jumbotron {
            padding-top: 30px;
            padding-bottom: 30px;
            margin-bottom: 30px;
            color: inherit;
            background-color: #eee;
        }

            .jumbotron .h1, .jumbotron h1 {
                color: inherit;
            }

            .jumbotron p {
                margin-bottom: 15px;
                font-size: 21px;
                font-weight: 200;
            }

            .jumbotron > hr {
                border-top-color: #d5d5d5;
            }

        .container .jumbotron, .container-fluid .jumbotron {
            padding-right: 15px;
            padding-left: 15px;
            border-radius: 6px;
        }

        .jumbotron .container {
            max-width: 100%;
        }

        @media screen and (min-width:768px) {
            .jumbotron {
                padding-top: 48px;
                padding-bottom: 48px;
            }

            .container .jumbotron, .container-fluid .jumbotron {
                padding-right: 60px;
                padding-left: 60px;
            }

            .jumbotron .h1, .jumbotron h1 {
                font-size: 63px;
            }
        }

        .thumbnail {
            display: block;
            padding: 4px;
            margin-bottom: 20px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: border .2s ease-in-out;
            -o-transition: border .2s ease-in-out;
            transition: border .2s ease-in-out;
        }

            .thumbnail a > img, .thumbnail > img {
                margin-right: auto;
                margin-left: auto;
            }

        a.thumbnail.active, a.thumbnail:focus, a.thumbnail:hover {
            border-color: #337ab7;
        }

        .thumbnail .caption {
            padding: 9px;
            color: #333;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

            .alert h4 {
                margin-top: 0;
                color: inherit;
            }

            .alert .alert-link {
                font-weight: 700;
            }

            .alert > p, .alert > ul {
                margin-bottom: 0;
            }

                .alert > p + p {
                    margin-top: 5px;
                }

        .alert-dismissable, .alert-dismissible {
            padding-right: 35px;
        }

            .alert-dismissable .close, .alert-dismissible .close {
                position: relative;
                top: -2px;
                right: -21px;
                color: inherit;
            }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

            .alert-success hr {
                border-top-color: #c9e2b3;
            }

            .alert-success .alert-link {
                color: #2b542c;
            }

        .alert-info {
            color: #31708f;
            background-color: #d9edf7;
            border-color: #bce8f1;
        }

            .alert-info hr {
                border-top-color: #a6e1ec;
            }

            .alert-info .alert-link {
                color: #245269;
            }

        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }

            .alert-warning hr {
                border-top-color: #f7e1b5;
            }

            .alert-warning .alert-link {
                color: #66512c;
            }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

            .alert-danger hr {
                border-top-color: #e4b9c0;
            }

            .alert-danger .alert-link {
                color: #843534;
            }

        @-webkit-keyframes progress-bar-stripes {
            from {
                background-position: 40px 0;
            }

            to {
                background-position: 0 0;
            }
        }

        @-o-keyframes progress-bar-stripes {
            from {
                background-position: 40px 0;
            }

            to {
                background-position: 0 0;
            }
        }

        @keyframes progress-bar-stripes {
            from {
                background-position: 40px 0;
            }

            to {
                background-position: 0 0;
            }
        }


        .list-group {
            padding-left: 0;
            margin-bottom: 20px;
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 10px 15px;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

            .list-group-item:first-child {
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
            }

            .list-group-item:last-child {
                margin-bottom: 0;
                border-bottom-right-radius: 4px;
                border-bottom-left-radius: 4px;
            }

        .panel {
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }

        .panel-body {
            padding: 15px;
        }

        .panel-heading {
            padding: 10px 15px;
            border-bottom: 1px solid transparent;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
        }

            .panel-heading > .dropdown .dropdown-toggle {
                color: inherit;
            }

        .panel-title {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 16px;
            color: inherit;
        }

            .panel-title > .small, .panel-title > .small > a, .panel-title > a, .panel-title > small, .panel-title > small > a {
                color: inherit;
            }

        .panel-footer {
            padding: 10px 15px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .panel > .list-group, .panel > .panel-collapse > .list-group {
            margin-bottom: 0;
        }

            .panel > .list-group .list-group-item, .panel > .panel-collapse > .list-group .list-group-item {
                border-width: 1px 0;
                border-radius: 0;
            }

            .panel > .list-group:first-child .list-group-item:first-child, .panel > .panel-collapse > .list-group:first-child .list-group-item:first-child {
                border-top: 0;
                border-top-left-radius: 3px;
                border-top-right-radius: 3px;
            }

            .panel > .list-group:last-child .list-group-item:last-child, .panel > .panel-collapse > .list-group:last-child .list-group-item:last-child {
                border-bottom: 0;
                border-bottom-right-radius: 3px;
                border-bottom-left-radius: 3px;
            }

        .panel > .panel-heading + .panel-collapse > .list-group .list-group-item:first-child {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .panel-heading + .list-group .list-group-item:first-child {
            border-top-width: 0;
        }

        .list-group + .panel-footer {
            border-top-width: 0;
        }

        .panel > .panel-collapse > .table, .panel > .table, .panel > .table-responsive > .table {
            margin-bottom: 0;
        }

            .panel > .panel-collapse > .table caption, .panel > .table caption, .panel > .table-responsive > .table caption {
                padding-right: 15px;
                padding-left: 15px;
            }

            .panel > .table-responsive:first-child > .table:first-child, .panel > .table:first-child {
                border-top-left-radius: 3px;
                border-top-right-radius: 3px;
            }

                .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child, .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child, .panel > .table:first-child > tbody:first-child > tr:first-child, .panel > .table:first-child > thead:first-child > tr:first-child {
                    border-top-left-radius: 3px;
                    border-top-right-radius: 3px;
                }

                    .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child td:first-child, .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child th:first-child, .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child td:first-child, .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child th:first-child, .panel > .table:first-child > tbody:first-child > tr:first-child td:first-child, .panel > .table:first-child > tbody:first-child > tr:first-child th:first-child, .panel > .table:first-child > thead:first-child > tr:first-child td:first-child, .panel > .table:first-child > thead:first-child > tr:first-child th:first-child {
                        border-top-left-radius: 3px;
                    }

                    .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child td:last-child, .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child th:last-child, .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child td:last-child, .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child th:last-child, .panel > .table:first-child > tbody:first-child > tr:first-child td:last-child, .panel > .table:first-child > tbody:first-child > tr:first-child th:last-child, .panel > .table:first-child > thead:first-child > tr:first-child td:last-child, .panel > .table:first-child > thead:first-child > tr:first-child th:last-child {
                        border-top-right-radius: 3px;
                    }

            .panel > .table-responsive:last-child > .table:last-child, .panel > .table:last-child {
                border-bottom-right-radius: 3px;
                border-bottom-left-radius: 3px;
            }

                .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child, .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child, .panel > .table:last-child > tbody:last-child > tr:last-child, .panel > .table:last-child > tfoot:last-child > tr:last-child {
                    border-bottom-right-radius: 3px;
                    border-bottom-left-radius: 3px;
                }

                    .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child td:first-child, .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child th:first-child, .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child td:first-child, .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child th:first-child, .panel > .table:last-child > tbody:last-child > tr:last-child td:first-child, .panel > .table:last-child > tbody:last-child > tr:last-child th:first-child, .panel > .table:last-child > tfoot:last-child > tr:last-child td:first-child, .panel > .table:last-child > tfoot:last-child > tr:last-child th:first-child {
                        border-bottom-left-radius: 3px;
                    }

                    .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child td:last-child, .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child th:last-child, .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child td:last-child, .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child th:last-child, .panel > .table:last-child > tbody:last-child > tr:last-child td:last-child, .panel > .table:last-child > tbody:last-child > tr:last-child th:last-child, .panel > .table:last-child > tfoot:last-child > tr:last-child td:last-child, .panel > .table:last-child > tfoot:last-child > tr:last-child th:last-child {
                        border-bottom-right-radius: 3px;
                    }

            .panel > .panel-body + .table, .panel > .panel-body + .table-responsive, .panel > .table + .panel-body, .panel > .table-responsive + .panel-body {
                border-top: 1px solid #ddd;
            }

            .panel > .table > tbody:first-child > tr:first-child td, .panel > .table > tbody:first-child > tr:first-child th {
                border-top: 0;
            }

        .panel > .table-bordered, .panel > .table-responsive > .table-bordered {
            border: 0;
        }

            .panel > .table-bordered > tbody > tr > td:first-child, .panel > .table-bordered > tbody > tr > th:first-child, .panel > .table-bordered > tfoot > tr > td:first-child, .panel > .table-bordered > tfoot > tr > th:first-child, .panel > .table-bordered > thead > tr > td:first-child, .panel > .table-bordered > thead > tr > th:first-child, .panel > .table-responsive > .table-bordered > tbody > tr > td:first-child, .panel > .table-responsive > .table-bordered > tbody > tr > th:first-child, .panel > .table-responsive > .table-bordered > tfoot > tr > td:first-child, .panel > .table-responsive > .table-bordered > tfoot > tr > th:first-child, .panel > .table-responsive > .table-bordered > thead > tr > td:first-child, .panel > .table-responsive > .table-bordered > thead > tr > th:first-child {
                border-left: 0;
            }

            .panel > .table-bordered > tbody > tr > td:last-child, .panel > .table-bordered > tbody > tr > th:last-child, .panel > .table-bordered > tfoot > tr > td:last-child, .panel > .table-bordered > tfoot > tr > th:last-child, .panel > .table-bordered > thead > tr > td:last-child, .panel > .table-bordered > thead > tr > th:last-child, .panel > .table-responsive > .table-bordered > tbody > tr > td:last-child, .panel > .table-responsive > .table-bordered > tbody > tr > th:last-child, .panel > .table-responsive > .table-bordered > tfoot > tr > td:last-child, .panel > .table-responsive > .table-bordered > tfoot > tr > th:last-child, .panel > .table-responsive > .table-bordered > thead > tr > td:last-child, .panel > .table-responsive > .table-bordered > thead > tr > th:last-child {
                border-right: 0;
            }

            .panel > .table-bordered > tbody > tr:first-child > td, .panel > .table-bordered > tbody > tr:first-child > th, .panel > .table-bordered > thead > tr:first-child > td, .panel > .table-bordered > thead > tr:first-child > th, .panel > .table-responsive > .table-bordered > tbody > tr:first-child > td, .panel > .table-responsive > .table-bordered > tbody > tr:first-child > th, .panel > .table-responsive > .table-bordered > thead > tr:first-child > td, .panel > .table-responsive > .table-bordered > thead > tr:first-child > th {
                border-bottom: 0;
            }

            .panel > .table-bordered > tbody > tr:last-child > td, .panel > .table-bordered > tbody > tr:last-child > th, .panel > .table-bordered > tfoot > tr:last-child > td, .panel > .table-bordered > tfoot > tr:last-child > th, .panel > .table-responsive > .table-bordered > tbody > tr:last-child > td, .panel > .table-responsive > .table-bordered > tbody > tr:last-child > th, .panel > .table-responsive > .table-bordered > tfoot > tr:last-child > td, .panel > .table-responsive > .table-bordered > tfoot > tr:last-child > th {
                border-bottom: 0;
            }

        .panel > .table-responsive {
            margin-bottom: 0;
            border: 0;
        }

        .panel-group {
            margin-bottom: 20px;
        }

            .panel-group .panel {
                margin-bottom: 0;
                border-radius: 4px;
            }

                .panel-group .panel + .panel {
                    margin-top: 5px;
                }

            .panel-group .panel-heading {
                border-bottom: 0;
            }

                .panel-group .panel-heading + .panel-collapse > .list-group, .panel-group .panel-heading + .panel-collapse > .panel-body {
                    border-top: 1px solid #ddd;
                }

            .panel-group .panel-footer {
                border-top: 0;
            }

                .panel-group .panel-footer + .panel-collapse .panel-body {
                    border-bottom: 1px solid #ddd;
                }

        .panel-default {
            border-color: #ddd;
        }

            .panel-default > .panel-heading {
                color: #333;
                background-color: #f5f5f5;
                border-color: #ddd;
            }

                .panel-default > .panel-heading + .panel-collapse > .panel-body {
                    border-top-color: #ddd;
                }

                .panel-default > .panel-heading .badge {
                    color: #f5f5f5;
                    background-color: #333;
                }

            .panel-default > .panel-footer + .panel-collapse > .panel-body {
                border-bottom-color: #ddd;
            }

        .panel-primary {
            border-color: #337ab7;
        }

            .panel-primary > .panel-heading {
                color: #fff;
                background-color: #337ab7;
                border-color: #337ab7;
            }

                .panel-primary > .panel-heading + .panel-collapse > .panel-body {
                    border-top-color: #337ab7;
                }

                .panel-primary > .panel-heading .badge {
                    color: #337ab7;
                    background-color: #fff;
                }

            .panel-primary > .panel-footer + .panel-collapse > .panel-body {
                border-bottom-color: #337ab7;
            }

        .panel-success {
            border-color: #d6e9c6;
        }

            .panel-success > .panel-heading {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }

                .panel-success > .panel-heading + .panel-collapse > .panel-body {
                    border-top-color: #d6e9c6;
                }

                .panel-success > .panel-heading .badge {
                    color: #dff0d8;
                    background-color: #3c763d;
                }

            .panel-success > .panel-footer + .panel-collapse > .panel-body {
                border-bottom-color: #d6e9c6;
            }

        .panel-info {
            border-color: #bce8f1;
        }

            .panel-info > .panel-heading {
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
            }

                .panel-info > .panel-heading + .panel-collapse > .panel-body {
                    border-top-color: #bce8f1;
                }

                .panel-info > .panel-heading .badge {
                    color: #d9edf7;
                    background-color: #31708f;
                }

            .panel-info > .panel-footer + .panel-collapse > .panel-body {
                border-bottom-color: #bce8f1;
            }

        .panel-warning {
            border-color: #faebcc;
        }

            .panel-warning > .panel-heading {
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
            }

                .panel-warning > .panel-heading + .panel-collapse > .panel-body {
                    border-top-color: #faebcc;
                }

                .panel-warning > .panel-heading .badge {
                    color: #fcf8e3;
                    background-color: #8a6d3b;
                }

            .panel-warning > .panel-footer + .panel-collapse > .panel-body {
                border-bottom-color: #faebcc;
            }

        .panel-danger {
            border-color: #ebccd1;
        }

            .panel-danger > .panel-heading {
                color: #a94442;
                background-color: #f2dede;
                border-color: #ebccd1;
            }

                .panel-danger > .panel-heading + .panel-collapse > .panel-body {
                    border-top-color: #ebccd1;
                }

                .panel-danger > .panel-heading .badge {
                    color: #f2dede;
                    background-color: #a94442;
                }

            .panel-danger > .panel-footer + .panel-collapse > .panel-body {
                border-bottom-color: #ebccd1;
            }

        .embed-responsive {
            position: relative;
            display: block;
            height: 0;
            padding: 0;
            overflow: hidden;
        }

            .embed-responsive .embed-responsive-item, .embed-responsive embed, .embed-responsive iframe, .embed-responsive object, .embed-responsive video {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }

        .embed-responsive-16by9 {
            padding-bottom: 56.25%;
        }

        .embed-responsive-4by3 {
            padding-bottom: 75%;
        }

        .invoice-col {
            width: 25%;
            float: left;
        }

        p.lead {
            margin-bottom: 0;
            line-height: normal;
			font-size:18px !important;
        }

            p.lead + .table-responsive {
                margin-top: 10px;
				font-size:18px !important;
            }

        @media print {
            .dont-break {
                page-break-inside: avoid;
            }
        }

        .clearfix:before,
        .clearfix:after {
            display: table;
            content: " ";
        }

        .clearfix:after {
            clear: both;
        }

        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }

            .well blockquote {
                border-color: #ddd;
                border-color: rgba(0, 0, 0, .15);
            }

        .well-lg {
            padding: 24px;
            border-radius: 6px;
        }

        .well-sm {
            padding: 9px;
            border-radius: 3px;
        }
        .table.border-0 > tbody > tr > th, .table.border-0 > tbody > tr > td{
            border-top: 0;
        }
		
.print-class
{
	padding:40px !important;
}
.paddi-z
{
	padd-left:0px !important;
}
.cl
{
	margin-bottom: 15px;
    font-size: 18px;
    text-transform: uppercase;
    font-weight: 600;
}
.img-tag {
    display: inline-block;
    height: 115px;
}
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
  <body onload="window.print();" class="print-class">
    <div class="wrapper" id="in-v">
      <!-- Example row of columns -->
            <?php
            /*
              Author : Rupesh Jorkar(RJ)
              Desc   : Call Page Content Area
              Input  : View Page Name and Bunch of array
              Output : View Page
              Date   : 11/03/2016
             */
            $this->load->view($main_content);
            ?>
    </div>
    <!-- AdminLTE App -->
  </body>
</html>
