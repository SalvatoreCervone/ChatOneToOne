<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ChatComponent2 from './ChatComponent2.vue';
import { defineProps } from 'vue';

const props = defineProps({
    auth: null
})
</script>

<template>
    <!--
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"> -->
    <ChatComponent2 v-if="$page.props.auth.user" :currentUser="$page.props.auth.user"></ChatComponent2>
    <!-- </div>
            </div>
        </div>
    </AuthenticatedLayout> -->
</template>
