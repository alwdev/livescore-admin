@extends('layouts.admin')

@section('title', 'Website Settings')
@section('header', 'Website Settings')
@section('description', 'Manage your website branding, contact info, and SEO configurations')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-10">
            @csrf

            <!-- 🏷️ General Website Info -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-2-4H5a2 2 0 00-2 2v0a2 2 0 002 2h14a2 2 0 002-2v0a2 2 0 00-2-2z" />
                        </svg>
                        General Website Info
                    </h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Site Name -->
                    <div>
                        <label for="site_name" class="block text-sm font-semibold text-gray-700 mb-2">Site Name</label>
                        <input type="text" id="site_name" name="site_name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('site_name', $settings->site_name) }}" placeholder="Enter website name">
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">Contact
                            Email</label>
                        <input type="email" id="contact_email" name="contact_email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('contact_email', $settings->contact_email) }}"
                            placeholder="contact@yourdomain.com">
                    </div>

                    <!-- Upload Logo -->
                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">Website Logo</label>
                        <div class="flex items-center gap-6">
                            <div class="flex-shrink-0">
                                @if ($settings->logo)
                                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo"
                                        class="h-16 w-auto rounded border border-gray-200 bg-white p-2">
                                @else
                                    <div
                                        class="h-16 w-16 bg-gray-100 border border-gray-200 rounded flex items-center justify-center text-gray-400">
                                        No Logo
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="logo" id="logo"
                                class="text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Recommended: PNG transparent background (max 1MB)</p>
                    </div>
                </div>
            </div>

            <!-- SEO Meta Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-green-50 border-b border-gray-200 px-6 py-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">SEO Meta Information</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Meta Title -->
                    <div>
                        <label for="seo_title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Meta Title
                        </label>
                        <input type="text" id="seo_title" name="seo_title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                            placeholder="Enter the SEO title for your homepage"
                            value="{{ old('seo_title', $settings->seo_title) }}">
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="seo_description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <textarea id="seo_description" name="seo_description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                            placeholder="Enter a short description for search engines">{{ old('seo_description', $settings->seo_description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommended: 150–160 characters</p>
                    </div>

                    <!-- ✅ Meta Keywords -->
                    <div>
                        <label for="seo_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                            Meta Keywords
                        </label>
                        <input type="text" id="seo_keywords" name="seo_keywords"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                            placeholder="e.g. livescore, football, live results, soccer news"
                            value="{{ old('seo_keywords', $settings->seo_keywords) }}">
                        <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                    </div>
                </div>
            </div>

            <!-- 📞 Contact & Social -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5h2l.344 2.758A2 2 0 007.322 9h9.356a2 2 0 001.978-1.242L19 5h2M4 5v14a2 2 0 002 2h12a2 2 0 002-2V5" />
                        </svg>
                        Contact & Social Links
                    </h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook" class="block text-sm font-semibold text-gray-700 mb-2">Facebook</label>
                        <input type="text" id="facebook" name="facebook"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('facebook', $settings->facebook) }}"
                            placeholder="https://facebook.com/yourpage">
                    </div>

                    <div>
                        <label for="twitter" class="block text-sm font-semibold text-gray-700 mb-2">Twitter (X)</label>
                        <input type="text" id="twitter" name="twitter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('twitter', $settings->twitter) }}" placeholder="https://x.com/yourprofile">
                    </div>

                    <div>
                        <label for="instagram" class="block text-sm font-semibold text-gray-700 mb-2">Instagram</label>
                        <input type="text" id="instagram" name="instagram"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                            value="{{ old('instagram', $settings->instagram) }}"
                            placeholder="https://instagram.com/yourpage">
                    </div>

                    <div>
                        <label for="youtube" class="block text-sm font-semibold text-gray-700 mb-2">YouTube</label>
                        <input type="text" id="youtube" name="youtube"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            value="{{ old('youtube', $settings->youtube) }}"
                            placeholder="https://youtube.com/yourchannel">
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium px-6 py-3 rounded-lg transition shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
