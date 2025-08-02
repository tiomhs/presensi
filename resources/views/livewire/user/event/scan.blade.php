<div>
    <div id="reader" style="width: 100%; max-width: 600px; margin: auto"></div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script type="text/javascript">
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let isScanned = false;

            function onScanSuccess(decodedText, decodedResult) {
                if (isScanned) return;

                isScanned = true; // biar gak spam request

                let eventId = {{ $eventId }}

                // console.log(`QR matched = ${decodedText}`);
                console.log(`QR matched = ${decodedText} for event ID: ${eventId}`);

                if (decodedText) {
                    window.location.href = `/user/event/${eventId}/scan/${decodedText}`;
                }else {
                    // Handle the case where the QR code is empty or invalid
                    console.error('Invalid QR code scanned');
                }

                
            }

            function onScanFailure(error) {
                // console.warn(`QR error = ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            }, false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        </script>
    @endpush
</div>
