<template>
    <i v-if="newmessage" class="pi pi-comment"></i>
</template>
<script setup>
import {  defineProps } from 'vue';
const props = defineProps({
    friend: null
})
</script>
