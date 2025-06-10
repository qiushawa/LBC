<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<style>
    body {
        font-family: "Segoe UI", "Noto Sans TC", sans-serif;
        background-color: #fdfdfd;
    }

    .ad-banner-title {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 2.5em;
        font-weight: bold;
        text-align: center;
        z-index: 2;
        /* 要高於遮罩 */
        padding: 0 1rem;
        word-break: break-word;
    }

    .ad-banner {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
        background-size: cover;
        background-position: top center;
        background-attachment: fixed;
        /* 背景固定不動 */
        margin-bottom: 2rem;
    }

    /* 遮罩效果：由上往下黑到透明 */
    .ad-banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.63), transparent);
        z-index: 1;
        pointer-events: none;
    }

    .markdown-body h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 700;
        color: #1a1a1a;
        margin-top: 2em;
        margin-bottom: 0.5em;
    }

    .markdown-body h1 {
        margin-top: 50px;
        font-size: 2.2em;
        border-bottom: 2px solid #007acc;
        padding-bottom: 0.3em;
    }

    .markdown-body h2 {
        margin-top: 30px;
        font-size: 1.8em;
        color: #007acc;
    }

    .markdown-body h3 {
        margin-top: 25px;
        font-size: 1.5em;
        color: #444;
    }

    .markdown-body p {
        margin: 1em 0;
        font-size: 1.1em;
    }

    .markdown-body ul,
    ol {
        padding-left: 1.5em;
        margin: 1em 0;
    }

    .markdown-body li {
        margin-bottom: 0.5em;
    }

    .markdown-body blockquote {
        border-left: 4px solid #007acc;
        background-color: #f0f8ff;
        padding: 1em;
        margin: 1.5em 0;
        font-style: italic;
        color: #333;
    }

    .markdown-body code {
        background-color: #f4f4f4;
        padding: 0.2em 0.4em;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.95em;
    }

    .markdown-body pre {
        background-color: #272822;
        color: #f8f8f2;
        padding: 1em;
        border-radius: 8px;
        overflow-x: auto;
        font-family: monospace;
    }

    .markdown-body a {
        color: #007acc;
        text-decoration: none;
    }

    .markdown-body a:hover {
        text-decoration: underline;
    }

    .markdown-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1em 0;
    }

    .markdown-body table,
    th,
    td {
        border: 1px solid #ccc;
    }

    .markdown-body th,
    td {
        padding: 0.5em;
        text-align: left;
    }

    .markdown-body hr {
        border: none;
        border-top: 1px solid #ddd;
        margin: 2em 0;
    }

    /* 特別強調區塊（可加 class） */
    .markdown-body .highlight {
        background-color: #fffbe6;
        border-left: 4px solid #ffc107;
        padding: 1em;
        margin: 1.5em 0;
    }

    /* 客戶回饋區塊 */
    .markdown-body .testimonial {
        background-color: #f0f8ff;
        border-left: 4px solid #00bcd4;
        padding: 1em;
        margin: 1.5em 0;
    }
</style>
@extends('layouts.app', ['page_name' => 'shop.ad', 'show_footer' => true, 'show_header' => false])
@section('content')
    <!-- 廣告橫幅圖片 -->
    <div class="ad-banner" style="background-image: url('{{ asset('images/ads/' . $ad->ad_banner . '.png?t=' . time()) }}');">
        <div class="ad-banner-overlay"></div>
        <div class="ad-banner-title">
            {{ $ad->ad_title }}
        </div>
    </div>
    <div class="container p-6 mx-auto">
        <div class="markdown-body">
            {!! $content_html !!}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>
@endsection
