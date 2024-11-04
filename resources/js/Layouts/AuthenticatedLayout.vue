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

    <nav class="bg-white border-gray-200 dark:bg-gray-900 border">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a :href="route('home')" class="flex items-center space-x-3 rtl:space-x-reverse">
                <!-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> -->
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="#1C64F2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                </svg>

                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">CHESU Doc</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a :href="route('student.dashboard')"
                            :class="{ 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 me-1 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800': route().current('student.dashboard'), 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent': !route().current('student.dashboard') }">
                            Мои заявки(студент)</a>
                    </li>
                    <li>
                        <a :href="route('student.send')"
                            :class="{ 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-1 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800': route().current('student.send'), 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent': !route().current('student.send') }">
                            Отправить заявку(студент)</a>
                    </li>
                    <li>
                        <a :href="route('methodologist.dashboard')"
                            :class="{ 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-1 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800': route().current('methodologist.dashboard'), 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent': !route().current('methodologist.dashboard') }">
                            Заявки от студентов(методист)</a>
                    </li>
                    <li>
                        <a :href="route('department.dashboard')"
                            :class="{ 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-1 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800': route().current('department.dashboard'), 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent': !route().current('department.dashboard') }">
                            Заявки от студентов(отдел)</a>
                    </li>
                    <li>
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        {{ $page.props.auth.user.name }}

                                        <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </li>
                </ul>
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
