<!-- Payment Method Section (Displayed only for paid events) -->
@if(!$event->is_free)
    <div class="mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h4>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Pilih Metode Pembayaran
                </label>
                <select name="payment_method" id="payment_method" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                    <option value="">Pilih metode pembayaran</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>

            <!-- Bank Transfer Section (Displayed when bank transfer is selected) -->
            <div id="bank_transfer_section" class="hidden space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="font-medium text-gray-900 mb-2">Informasi Rekening</h5>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>Bank: BCA</p>
                        <p>No. Rekening: 1234567890</p>
                        <p>Atas Nama: PT Makna Academy</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Upload Bukti Pembayaran
                    </label>
                    <div class="mt-1">
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                            accept="image/jpeg,image/png,image/jpg,application/pdf"
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">
                            Format yang diterima: JPEG, PNG, JPG, PDF (Maks. 2MB)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@section('scripts')
<script>
document.getElementById('payment_method')?.addEventListener('change', function() {
    const bankTransferSection = document.getElementById('bank_transfer_section');
    const buktiPembayaranInput = document.getElementById('bukti_pembayaran');
    
    if (this.value === 'bank_transfer') {
        bankTransferSection.classList.remove('hidden');
        buktiPembayaranInput.required = true;
    } else {
        bankTransferSection.classList.add('hidden');
        buktiPembayaranInput.required = false;
    }
});
</script>
@endsection
