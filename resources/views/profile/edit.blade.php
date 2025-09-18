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
                    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                        <h3 class="text-lg font-semibold text-red-900 mb-2">Delete Account</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Menghapus akun akan menghapus semua data Anda secara permanen.
                        </p>
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to delete your account?')"
                                class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 transition duration-300">
                                Delete Account
                            </button>
                        </form>
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
