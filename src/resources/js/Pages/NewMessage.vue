<template>
    <i v-if="unreadCount > 0" class="pi pi-comment"></i>
</template>
<script setup>
import { defineProps } from 'vue'
defineProps({
    unreadCount: { type: Number, default: 0 }
})
</script>
