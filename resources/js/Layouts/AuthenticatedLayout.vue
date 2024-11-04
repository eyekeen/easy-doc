<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const logout = () => {
    axios.post('/logout')
        .then(response => {
            location.reload();
            console.log('Response:', response.data); // Handle success
        })
        .catch(error => {
            console.error('Error:', error); // Handle error
        });
}

const showingNavigationDropdown = ref(false);
</script>

<template>

    <nav className="bg-white shadow-lg border">
        <div className="container mx-auto px-4">
            <div className="flex justify-between items-center h-16">
                <div className="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-graduation-cap h-8 w-8 text-blue-600">
                        <path
                            d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z">
                        </path>
                        <path d="M22 10v6"></path>
                        <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                    </svg>
                    <span className="text-xl font-bold text-gray-900">CHESU Doc</span>
                </div>

                <div className="flex items-center space-x-4" v-if="$page.props.auth.user.role == 1">
                    <Link :href="route('student.dashboard')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('student.dashboard'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('student.dashboard') }">
                    Мои заявки
                    </Link>
                    <Link :href="route('student.send')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('student.send'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('student.send') }">
                    Отправить заявку
                    </Link>
                    <Link :href="route('profile.edit')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('profile.edit'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('profile.edit') }">
                    Профиль
                    </Link>

                    <button @click="logout"
                        className="px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-out h-4 w-4">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" x2="9" y1="12" y2="12"></line>
                        </svg>
                        <span>Выйти</span>
                    </button>
                </div>
                <div className="flex items-center space-x-4" v-if="$page.props.auth.user.role == 2">
                    <Link :href="route('methodologist.dashboard')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('methodologist.dashboard'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('methodologist.dashboard') }">
                    Заявки от студентов
                    </Link>
                    <Link :href="route('profile.edit')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('profile.edit'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('profile.edit') }">
                    Профиль
                    </Link>

                    <button @click="logout"
                        className="px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-out h-4 w-4">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" x2="9" y1="12" y2="12"></line>
                        </svg>
                        <span>Выйти</span>
                    </button>
                </div>
                <div className="flex items-center space-x-4" v-if="$page.props.auth.user.role == 3">
                    <Link :href="route('department.dashboard')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('department.dashboard'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('department.dashboard') }">
                    Заявки от студентов одобренные методистом
                    </Link>
                    <Link :href="route('profile.edit')"
                        :class="{ 'px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700': route().current('profile.edit'), 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100': !route().current('profile.edit') }">
                    Профиль
                    </Link>

                    <button @click="logout"
                        className="px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-out h-4 w-4">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" x2="9" y1="12" y2="12"></line>
                        </svg>
                        <span>Выйти</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div>
        <div class="min-h-screen bg-gray-100">


            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
