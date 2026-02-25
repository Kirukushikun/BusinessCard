@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $card->name }} — Digital Business Card</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/ico" href="{{ asset('img/BFC.ico') }}" />
        <style>
            :root {
                --orange: #ec891b;
                --maroon: #ab0b37;
            }
            *,
            *::before,
            *::after {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                padding: 40px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 28px;
                font-family: "Poppins", sans-serif;
                background-color: #f5f0eb;
                background-image: radial-gradient(ellipse at 15% 30%, rgba(236, 137, 27, 0.12) 0%, transparent 55%), radial-gradient(ellipse at 85% 70%, rgba(171, 11, 55, 0.08) 0%, transparent 55%);
                position: relative;
                overflow-x: hidden;
            }

            /* Doodle canvas */
            #doodle-bg {
                position: fixed;
                inset: 0;
                z-index: 0;
                pointer-events: none;
            }

            .page-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 24px;
                width: 100%;
                position: relative;
                z-index: 1;
                animation: fadeUp 0.7s ease both;
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Company header */
            .company-header {
                text-align: center;
            }

            .company-logo {
                width: 150px;
                height: auto;
                object-fit: contain;
            }

            .company-tagline {
                font-size: 10px;
                font-weight: 500;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                margin-top: 8px;
                color: #a08060;
            }

            /* CARD */
            .biz-card {
                width: 100%;
                max-width: 750px;
                aspect-ratio: 1.75;
                border-radius: 22px;
                overflow: hidden;
                display: flex;
                position: relative;
                transition:
                    background 0.4s,
                    box-shadow 0.4s;
                background: #ffffff;
                border: 1px solid rgba(0, 0, 0, 0.07);
                box-shadow:
                    0 2px 4px rgba(0, 0, 0, 0.04),
                    0 12px 40px rgba(0, 0, 0, 0.12),
                    0 40px 80px rgba(0, 0, 0, 0.08);
            }

            /* Brand panel */
            .brand-panel {
                width: 38%;
                flex-shrink: 0;
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 28px 25px 25px 28px;
                overflow: hidden;
                background: linear-gradient(150deg, #1a0a05 0%, #2d0d18 100%);
            }
            .brand-panel::before {
                content: "";
                position: absolute;
                top: -50px;
                right: -50px;
                width: 175px;
                height: 175px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(236, 137, 27, 0.25) 0%, transparent 70%);
                pointer-events: none;
            }
            .brand-panel::after {
                content: "";
                position: absolute;
                bottom: -38px;
                left: -25px;
                width: 150px;
                height: 150px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(171, 11, 55, 0.3) 0%, transparent 70%);
                pointer-events: none;
            }

            .brand-logo-wrap {
                position: relative;
                z-index: 1;
            }
            .brand-logo-placeholder {
                width: 65px;
                height: 40px;
                background: linear-gradient(135deg, var(--orange), var(--maroon));
                border-radius: 8px;
                opacity: 0.9;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 6px 10px;
            }

            .brand-logo {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
            .brand-bottom {
                position: relative;
                z-index: 1;
            }
            .brand-divider {
                width: 35px;
                height: 2px;
                background: linear-gradient(90deg, var(--orange), var(--maroon));
                border-radius: 2px;
                margin-bottom: 8px;
            }
            .brand-tagline {
                font-size: 10px;
                font-weight: 500;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.35);
                line-height: 1.5;
            }

            /* Info panel */
            .info-panel {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 25px 28px;
                position: relative;
                overflow: hidden;
            }
            .info-panel::after {
                content: "";
                position: absolute;
                top: -38px;
                right: -38px;
                width: 125px;
                height: 125px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(236, 137, 27, 0.06) 0%, transparent 70%);
                pointer-events: none;
            }

            .person-name {
                font-size: 20px;
                font-weight: 700;
                line-height: 1.15;
                margin-bottom: 4px;
                color: #1a1a1a;
            }
            .person-role {
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: var(--orange);
            }

            .contacts-block {
                display: flex;
                flex-direction: column;
                gap: 9px;
            }
            .contact-item {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .contact-item svg {
                width: 13px;
                height: 13px;
                stroke: var(--maroon);
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
                flex-shrink: 0;
                opacity: 0.8;
            }
            .contact-item a {
                font-size: 12px;
                color: #444;
                text-decoration: none;
                transition: color 0.2s;
            }
            .contact-item a:hover {
                color: var(--orange);
            }

            .bottom-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .save-btn {
                font-size: 9.5px;
                font-weight: 600;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: white;
                background: linear-gradient(90deg, var(--maroon), var(--orange));
                border: none;
                border-radius: 8px;
                padding: 9px 15px;
                cursor: pointer;
                font-family: "Poppins", sans-serif;
                text-decoration: none;
                display: inline-block;
                transition:
                    opacity 0.2s,
                    transform 0.15s;
            }
            .save-btn:hover {
                opacity: 0.88;
                transform: translateY(-1px);
            }

            .qr-group {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .qr-box {
                width: 52px;
                height: 52px;
                border-radius: 7px;
                flex-shrink: 0;
                background: white;
                padding: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(0, 0, 0, 0.07);
            }
            .qr-box svg {
                width: 100%;
                height: 100%;
            }

            .qr-scan-label {
                font-size: 9px;
                font-weight: 600;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                margin-bottom: 3px;
                color: #c0b0a0;
            }
            .qr-url {
                font-size: 10px;
                color: var(--orange);
                word-break: break-all;
                line-height: 1.3;
            }

            .page-footer {
                font-size: 10px;
                letter-spacing: 0.1em;
                color: rgba(0, 0, 0, 0.2);
                position: relative;
                z-index: 1;
            }

            /* Mobile */
            @media (max-width: 560px) {
                .biz-card {
                    aspect-ratio: unset;
                    flex-direction: column;
                }
                .brand-panel {
                    width: 100%;
                    flex-direction: row;
                    align-items: center;
                    padding: 16px 20px;
                    gap: 16px;
                }
                .brand-panel::before,
                .brand-panel::after {
                    display: none;
                }
                .info-panel {
                    padding: 18px 20px;
                    gap: 14px;
                }
                .person-name {
                    font-size: 20px;
                }
                .contact-item a {
                    font-size: 13px;
                }
                .save-btn {
                    font-size: 10px;
                    padding: 10px 16px;
                }
                .qr-box {
                    width: 52px;
                    height: 52px;
                }
            }

            /* QR clickable hint */
            .qr-box {
                cursor: pointer;
                transition:
                    transform 0.2s,
                    box-shadow 0.2s;
            }
            .qr-box:hover {
                transform: scale(1.08);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            }

            /* QR Lightbox overlay */
            .qr-overlay {
                position: fixed;
                inset: 0;
                z-index: 9999; /* ← bump this up from 999 to 9999 */
                background: rgba(0, 0, 0, 0);
                display: flex;
                align-items: center;
                justify-content: center;
                pointer-events: none;
                transition: background 0.3s ease;
            }

            .qr-overlay.active {
                background: rgba(0, 0, 0, 0.75);
                pointer-events: all;
                backdrop-filter: blur(4px);
            }

            .qr-modal {
                background: white;
                border-radius: 20px;
                padding: 28px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 14px;
                transform: scale(0.85);
                opacity: 0;
                transition:
                    transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                    opacity 0.3s ease;
                box-shadow: 0 24px 80px rgba(0, 0, 0, 0.4);
            }

            .qr-overlay.active .qr-modal {
                transform: scale(1);
                opacity: 1;
            }

            .qr-modal-code {
                width: 220px;
                height: 220px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .qr-modal-code svg {
                width: 100%;
                height: 100%;
            }

            .qr-modal-label {
                font-size: 10px;
                font-weight: 600;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: #c0b0a0;
            }

            .qr-modal-url {
                font-size: 12px;
                color: var(--orange);
                text-align: center;
                word-break: break-all;
                line-height: 1.4;
            }

            .qr-modal-close {
                font-size: 9px;
                font-weight: 600;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: rgba(0, 0, 0, 0.3);
                cursor: pointer;
                transition: color 0.2s;
                background: none;
                border: none;
                font-family: "Poppins", sans-serif;
                padding: 4px 8px;
            }

            .qr-modal-close:hover {
                color: var(--maroon);
            }

            /* link share */
            .share-btn {
                font-weight: 600;
                cursor: pointer;
                /* text-decoration: underline; */
                display: inline-block; /* ← add this */
                transition: transform 0.2s; /* ← add this for smooth animation */
            }

            .share-btn:hover {
                color: var(--maroon);
                transform: translate(0, -2px); /* ← subtle lift on hover */
            }
            .share-btn svg {
                width: 9px;
                height: 8px;
            }
            .share-icon-btn {
                background: none;
                border: none;
                padding: 0;
                margin-left: 5px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                vertical-align: middle;
                opacity: 0.5;
                transition: opacity 0.2s;
            }
            .share-icon-btn:hover { opacity: 1; }


            .qr-url {
                font-size: 10px;
                color: var(--orange);
                word-break: break-all;
                line-height: 1.3;
                cursor: pointer;
                transition: opacity 0.2s;
                text-decoration: underline dotted;
                text-underline-offset: 2px;
            }
            .qr-url:hover { opacity: 0.75; }

            .qr-url-block {
                position: relative;
            }

            .copy-bubble {
                position: absolute;
                bottom: calc(100% + 8px);
                left: 50%;
                transform: translateX(-50%) scale(0.8);
                background: #1a1a1a;
                color: white;
                font-size: 9px;
                font-weight: 600;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                padding: 5px 10px;
                border-radius: 8px;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .copy-bubble::after {
                content: '';
                position: absolute;
                top: 100%;
                left: 50%;
                transform: translateX(-50%);
                border: 5px solid transparent;
                border-top-color: #1a1a1a;
            }

            .copy-bubble.show {
                opacity: 1;
                transform: translateX(-50%) scale(1);
            }
        </style>
    </head>
    <body>
        <canvas id="doodle-bg"></canvas>

        <div class="page-wrapper">
            {{-- Company Header --}}
            <div class="company-header">
                {{-- Swap placeholder once you have logo: --}}
                <img src="{{ asset('img/BFC.png') }}" alt="Logo" class="company-logo" />
                <!-- <div class="brand-logo-placeholder" style="width:150px;height:56px;border-radius:8px;background:linear-gradient(135deg,#ec891b,#ab0b37);opacity:0.85;display:inline-block;"></div> -->
                <p class="company-tagline">What we do, we do best!</p>
            </div>

            {{-- Business Card --}}
            <div class="biz-card">
                {{-- Brand Panel --}}
                <div class="brand-panel">
                    <div class="brand-logo-wrap">
                        <div class="brand-logo-placeholder">
                            <img src="{{ asset('img/BFC-White.png') }}" alt="Logo" class="brand-logo" />
                        </div>
                    </div>
                    <div class="brand-bottom">
                        <div class="brand-divider"></div>
                        <div class="brand-tagline">What we do,<br />We do best!</div>
                    </div>
                </div>

                {{-- Info Panel --}}
                <div class="info-panel">
                    {{-- Name & Role --}}
                    <div class="name-block" style="display: flex; align-items: center; gap: 12px">
                        <div>
                            <div class="person-name">{{ $card->name }}</div>
                            <div class="person-role">{{ $card->position }}</div>
                        </div>
                    </div>

                    {{-- Contacts --}}
                    <div class="contacts-block">
                        @if($card->email)
                        <div class="contact-item">
                            <svg viewBox="0 0 24 24">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            </svg>
                            <a href="mailto:{{ $card->email }}">{{ $card->email }}</a>
                        </div>
                        @endif @if($card->work_phone)
                        <div class="contact-item">
                            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z" /></svg>
                            <a href="tel:{{ $card->work_phone }}">{{ $card->work_phone }}</a>
                        </div>
                        @endif @if($card->mobile)
                        <div class="contact-item">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="2" width="14" height="20" rx="2" />
                                <path d="M12 18h.01" />
                            </svg>
                            <a href="tel:{{ $card->mobile }}">{{ $card->mobile }}</a>
                        </div>
                        @endif
                    </div>

                    {{-- QR + Save --}}
                    <div class="bottom-row">
                        <a href="{{ route('card.vcard', $card->slug) }}" class="save-btn"> Save to Contacts </a>

                        <div class="qr-group">
                            <div class="qr-box" onclick="openQr()" title="Click to enlarge">
                                {!! $qrCode !!}
                            </div>
                            <div class="qr-url-block">
                                <div class="copy-bubble" id="copyBubble">Copied! 🎉</div>
                                <div class="qr-scan-label">
                                    Scan or 
                                    <span class="share-btn" onclick="shareCard()"> Share 
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="qr-url" id="qrUrl" onclick="copyLink()" title="Click to copy">
                                    {{ url('/card/' . $card->slug) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="page-footer">Powered by Digital Card System</div> -->
        </div>

        {{-- QR Lightbox --}}
        <div class="qr-overlay" id="qrOverlay">
            <div class="qr-modal" id="qrModal">
                <div class="qr-modal-label">Scan to view card</div>
                <div class="qr-modal-code">{!! $qrCode !!}</div>
                <div class="qr-modal-url">{{ url('/card/' . $card->slug) }}</div>
                <button class="qr-modal-close" onclick="closeQr()">Tap anywhere to close</button>
            </div>
        </div>

        <script>
            const canvas = document.getElementById("doodle-bg");
            const ctx = canvas.getContext("2d");
            let mouseX = -9999,
                mouseY = -9999;
            const PROXIMITY_RADIUS = 100;

            window.addEventListener("mousemove", (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                drawDoodles();
            });
            window.addEventListener("mouseleave", () => {
                mouseX = -9999;
                mouseY = -9999;
                drawDoodles();
            });

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                drawDoodles();
            }

            function drawDoodles() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.lineWidth = 1.4;
                ctx.lineCap = "round";
                ctx.lineJoin = "round";

                const iconSize = 36,
                    gap = 68;
                const cols = Math.ceil(canvas.width / gap) + 2;
                const rows = Math.ceil(canvas.height / gap) + 2;
                const icons = ["chicken", "egg", "chick", "pig"];
                let idx = 0;

                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        const offsetX = r % 2 === 0 ? 0 : gap / 2;
                        const x = c * gap + offsetX - gap;
                        const y = r * gap - gap;
                        const icon = icons[idx % icons.length];
                        idx++;
                        const dist = Math.sqrt((x - mouseX) ** 2 + (y - mouseY) ** 2);
                        const baseOpacity = 0.07,
                            hoverOpacity = 0.28;
                        let opacity = baseOpacity;
                        if (dist < PROXIMITY_RADIUS) {
                            const t = 1 - dist / PROXIMITY_RADIUS;
                            opacity = baseOpacity + (hoverOpacity - baseOpacity) * (t * t);
                        }
                        ctx.strokeStyle = `rgba(0,0,0,${opacity})`;
                        ctx.fillStyle = `rgba(0,0,0,${opacity})`;
                        ctx.save();
                        ctx.translate(x, y);
                        const seed = (r * 1000 + c) % 7;
                        ctx.rotate((seed - 3) * 0.08);
                        drawIcon(ctx, icon, iconSize);
                        ctx.restore();
                    }
                }
            }

            function drawIcon(ctx, type, s) {
                const h = s * 0.5;
                ctx.beginPath();
                if (type === "egg") {
                    ctx.save();
                    ctx.scale(1, 1.3);
                    ctx.arc(0, 0, h * 0.72, 0, Math.PI * 2);
                    ctx.restore();
                    ctx.stroke();
                } else if (type === "chicken") {
                    ctx.save();
                    ctx.scale(1.1, 1);
                    ctx.arc(0, h * 0.15, h * 0.7, 0, Math.PI * 2);
                    ctx.restore();
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.arc(h * 0.45, -h * 0.55, h * 0.32, 0, Math.PI * 2);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(h * 0.75, -h * 0.55);
                    ctx.lineTo(h * 1.0, -h * 0.45);
                    ctx.lineTo(h * 0.75, -h * 0.38);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(h * 0.3, -h * 0.87);
                    ctx.quadraticCurveTo(h * 0.38, -h * 1.1, h * 0.45, -h * 0.88);
                    ctx.quadraticCurveTo(h * 0.53, -h * 1.05, h * 0.6, -h * 0.87);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.6, -h * 0.3);
                    ctx.quadraticCurveTo(-h * 1.1, -h * 0.7, -h * 0.9, -h * 0.1);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.6, -h * 0.1);
                    ctx.quadraticCurveTo(-h * 1.2, -h * 0.3, -h * 0.85, h * 0.1);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.1, h * 0.82);
                    ctx.lineTo(-h * 0.1, h * 1.1);
                    ctx.moveTo(-h * 0.1, h * 1.1);
                    ctx.lineTo(-h * 0.35, h * 1.28);
                    ctx.moveTo(-h * 0.1, h * 1.1);
                    ctx.lineTo(h * 0.1, h * 1.3);
                    ctx.moveTo(-h * 0.1, h * 1.1);
                    ctx.lineTo(h * 0.28, h * 1.1);
                    ctx.moveTo(h * 0.3, h * 0.82);
                    ctx.lineTo(h * 0.3, h * 1.1);
                    ctx.moveTo(h * 0.3, h * 1.1);
                    ctx.lineTo(h * 0.05, h * 1.28);
                    ctx.moveTo(h * 0.3, h * 1.1);
                    ctx.lineTo(h * 0.5, h * 1.3);
                    ctx.moveTo(h * 0.3, h * 1.1);
                    ctx.lineTo(h * 0.65, h * 1.08);
                    ctx.stroke();
                } else if (type === "chick") {
                    ctx.save();
                    ctx.scale(1, 0.95);
                    ctx.arc(0, h * 0.2, h * 0.62, 0, Math.PI * 2);
                    ctx.restore();
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.arc(0, -h * 0.45, h * 0.38, 0, Math.PI * 2);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(h * 0.36, -h * 0.46);
                    ctx.lineTo(h * 0.6, -h * 0.38);
                    ctx.lineTo(h * 0.36, -h * 0.3);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.55, h * 0.1);
                    ctx.quadraticCurveTo(-h * 0.85, h * 0.3, -h * 0.5, h * 0.5);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.18, h * 0.8);
                    ctx.lineTo(-h * 0.18, h * 1.05);
                    ctx.moveTo(-h * 0.18, h * 1.05);
                    ctx.lineTo(-h * 0.38, h * 1.2);
                    ctx.moveTo(-h * 0.18, h * 1.05);
                    ctx.lineTo(h * 0.05, h * 1.22);
                    ctx.moveTo(h * 0.18, h * 0.8);
                    ctx.lineTo(h * 0.18, h * 1.05);
                    ctx.moveTo(h * 0.18, h * 1.05);
                    ctx.lineTo(-h * 0.02, h * 1.22);
                    ctx.moveTo(h * 0.18, h * 1.05);
                    ctx.lineTo(h * 0.4, h * 1.2);
                    ctx.stroke();
                } else if (type === "pig") {
                    ctx.save();
                    ctx.scale(1.1, 0.95);
                    ctx.arc(0, h * 0.2, h * 0.68, 0, Math.PI * 2);
                    ctx.restore();
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.arc(h * 0.38, -h * 0.48, h * 0.4, 0, Math.PI * 2);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.save();
                    ctx.scale(1.2, 0.85);
                    ctx.arc(h * 0.38, -h * 0.28, h * 0.22, 0, Math.PI * 2);
                    ctx.restore();
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(h * 0.15, -h * 0.82);
                    ctx.quadraticCurveTo(h * 0.05, -h * 1.08, h * 0.28, -h * 0.88);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(h * 0.5, -h * 0.84);
                    ctx.quadraticCurveTo(h * 0.65, -h * 1.08, h * 0.52, -h * 0.88);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.72, h * 0.05);
                    ctx.bezierCurveTo(-h * 1.05, -h * 0.1, -h * 1.1, h * 0.3, -h * 0.85, h * 0.25);
                    ctx.bezierCurveTo(-h * 0.65, h * 0.2, -h * 0.7, h * 0.0, -h * 0.88, h * 0.05);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-h * 0.3, h * 0.85);
                    ctx.lineTo(-h * 0.3, h * 1.15);
                    ctx.moveTo(h * 0.1, h * 0.85);
                    ctx.lineTo(h * 0.1, h * 1.15);
                    ctx.moveTo(-h * 0.55, h * 0.78);
                    ctx.lineTo(-h * 0.55, h * 1.08);
                    ctx.moveTo(h * 0.35, h * 0.78);
                    ctx.lineTo(h * 0.35, h * 1.08);
                    ctx.moveTo(-h * 0.38, h * 1.15);
                    ctx.lineTo(-h * 0.22, h * 1.15);
                    ctx.moveTo(h * 0.02, h * 1.15);
                    ctx.lineTo(h * 0.18, h * 1.15);
                    ctx.moveTo(-h * 0.63, h * 1.08);
                    ctx.lineTo(-h * 0.47, h * 1.08);
                    ctx.moveTo(h * 0.27, h * 1.08);
                    ctx.lineTo(h * 0.43, h * 1.08);
                    ctx.stroke();
                }
            }

            // QR Lightbox
            function openQr() {
                document.getElementById("qrOverlay").classList.add("active");
            }

            function closeQr() {
                document.getElementById("qrOverlay").classList.remove("active");
            }

            document.getElementById("qrOverlay").addEventListener("click", function (e) {
                if (e.target === this) closeQr();
            });

            // Close on ESC key
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape") closeQr();
            });

            window.addEventListener("resize", resize);
            resize();

            // Link Share
            const cardUrl = "{{ url('/card/' . $card->slug) }}";

            async function shareCard() {
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: '{{ $card->name }} — Digital Business Card',
                            text: 'View {{ $card->name }}\'s digital business card',
                            url: cardUrl,
                        });
                    } catch(e) {
                        // user cancelled, do nothing
                    }
                } else {
                    // fallback to copy if Web Share API not supported
                    copyLink();
                }
            }

            // ✅ Replace with this
            function copyLink() {
                navigator.clipboard.writeText(cardUrl).then(() => {
                    const bubble = document.getElementById('copyBubble');
                    bubble.classList.add('show');
                    setTimeout(() => bubble.classList.remove('show'), 2000);
                });
            }
        </script>
    </body>
</html>
