@extends('layouts.app')

@section('title', 'Edit Profile - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
                        <p class="text-gray-600">Kelola informasi profile dan keamanan akun Anda</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Informasi Profile</h2>
                            <p class="text-sm text-gray-600 mt-1">Update informasi profile dan email address Anda.</p>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            class="p-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Avatar Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                                <div class="flex items-center space-x-6">
                                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                                        <x-user-avatar :user="$user" :size="80" class="flex-shrink-0" />
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                                        <label for="avatar"
                                            class="inline-flex items-center cursor-pointer bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300">
                                            <i class="fas fa-camera mr-2"></i>
                                            Ubah Foto
                                        </label>
                                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 1MB</p>
                                    </div>
                                </div>
                                @error('avatar')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                
                                @if(session('avatar_error'))
                                    <p class="text-red-500 text-sm mt-1">{{ session('avatar_error') }}</p>
                                @endif
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" id="date_of_birth"
                                    value="{{ old('date_of_birth', $user->date_of_birth ? date('Y-m-d', strtotime($user->date_of_birth)) : '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                @error('date_of_birth')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis
                                    Kelamin</label>
                                <div class="relative w-full">
                                    <select name="gender" id="gender"
                                        class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 
               bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
               transition ease-in-out duration-200 cursor-pointer">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male"
                                            {{ old('gender', $user->gender ?? '') === 'male' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="female"
                                            {{ old('gender', $user->gender ?? '') === 'female' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>

                                    {{-- Ikon panah di sisi kanan --}}
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('gender')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 font-medium">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Update Form -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
                            <p class="text-sm text-gray-600 mt-1">Pastikan akun Anda menggunakan password yang kuat untuk
                                keamanan.</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="p-6 space-y-6">
                            @csrf
                            @method('put')

                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password"
                                        class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        autocomplete="current-password"
                                        required>
                                    <button type="button" onclick="togglePassword('current_password')"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                        class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        autocomplete="new-password"
                                        required
                                        minlength="8">
                                    <button type="button" onclick="togglePassword('password')"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        autocomplete="new-password"
                                        required>
                                    <button type="button" onclick="togglePassword('password_confirmation')"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-300 font-medium">
                                    <i class="fas fa-key mr-2"></i>
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Profile Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="text-green-600 font-medium">Active</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Member Since:</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Events Joined:</span>
                                <span class="text-gray-900">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}"
                                class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                            <a href="{{ route('events.index') }}"
                                class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition duration-200">
                                <i class="fas fa-calendar mr-2"></i>Lihat Event
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div x-data="{ showDeleteModal: false }" class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                {{-- <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div> --}}
                            </div>
                            <div class="flex-1">
                                {{-- <h3 class="text-lg font-semibold text-red-900 mb-2">Hapus Akun</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Setelah akun Anda dihapus, semua data dan informasi akan dihapus secara permanen. 
                                    Pastikan untuk mengunduh data yang ingin Anda simpan sebelum menghapus akun.
                                </p> --}}

                                <button @click="showDeleteModal = true"
                                    class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Akun
                                </button>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div x-show="showDeleteModal" 
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-50 overflow-y-auto"
                             style="display: none;">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                Konfirmasi Hapus Akun
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 mb-4">
                                                    Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan dan semua data Anda akan hilang secara permanen.
                                                </p>
                                                
                                                <!-- Warning List - Hanya tampil di modal -->
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                                    <h4 class="text-sm font-medium text-red-800 mb-2">Data yang akan dihapus:</h4>
                                                    <ul class="text-xs text-red-700 space-y-1">
                                                        <li>• Informasi profil dan akun</li>
                                                        <li>• Riwayat pendaftaran event</li>
                                                        <li>• Sertifikat dan materi pembelajaran</li>
                                                        <li>• Riwayat pembayaran</li>
                                                        <li>• Semua aktivitas dan log</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <!-- Password Confirmation -->
                                            <div class="mt-4">
                                                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                                                    @csrf
                                                    @method('delete')
                                                    
                                                    <div class="mb-4">
                                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                                            Masukkan password untuk konfirmasi:
                                                        </label>
                                                        <input type="password" 
                                                               name="password" 
                                                               id="password_confirmation"
                                                               required
                                                               placeholder="Password Anda"
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                                                    </div>
                                                    
                                                    <div class="flex items-center mb-4">
                                                        <input type="checkbox" 
                                                               id="understand_deletion" 
                                                               name="understand_deletion"
                                                               required
                                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                        <label for="understand_deletion" class="ml-2 block text-sm text-gray-700">
                                                            Saya memahami bahwa tindakan ini tidak dapat dibatalkan
                                                        </label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button type="submit" 
                                                form="deleteAccountForm"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Ya, Hapus Akun
                                        </button>
                                        <button @click="showDeleteModal = false"
                                                type="button" 
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center"
             role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <span>Profile berhasil diperbarui!</span>
        </div>
    @endif

    @if (session('status') === 'avatar-updated')
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center"
             role="alert">
            <i class="fas fa-camera mr-2"></i>
            <span>Foto profil berhasil diperbarui!</span>
        </div>
    @endif

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                // Validate file size (1MB = 1048576 bytes)
                if (input.files[0].size > 1048576) {
                    alert('Ukuran file terlalu besar. Maksimal 1MB.');
                    input.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(input.files[0].type)) {
                    alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Find the avatar container and update it
                    const avatarContainer = input.closest('.flex').querySelector('.w-20.h-20');
                    
                    // Create new image element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 rounded-full object-cover';
                    img.alt = 'Preview Avatar';
                    
                    // Replace content with smooth transition
                    avatarContainer.style.opacity = '0.5';
                    setTimeout(() => {
                        avatarContainer.innerHTML = '';
                        avatarContainer.appendChild(img);
                        avatarContainer.style.opacity = '1';
                    }, 150);
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    @if (session('status') === 'password-updated')
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center"
             role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <span>Password berhasil diperbarui!</span>
        </div>
    @endif
@endsection
