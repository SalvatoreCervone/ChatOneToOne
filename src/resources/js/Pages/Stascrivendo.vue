<template>
    <small v-if="isFriendTyping" class="text-gray-700">
        {{ friend.name }} sta scrivendo...
    </small>

</template>
<script setup>
import { ref, onMounted, defineProps } from 'vue';
const props = defineProps({
    current_user_id: null,
    friend: null
})

const isFriendTyping = ref(false);
const isFriendTypingTimer = ref(null);

onMounted(() => {


    Echo.private(`chat.${props.current_user_id}`)
        .listenForWhisper("typing", (response) => {
            isFriendTyping.value = response.userID === props.friend.id;

            if (isFriendTypingTimer.value) {
                clearTimeout(isFriendTypingTimer.value);
            }

            isFriendTypingTimer.value = setTimeout(() => {
                isFriendTyping.value = false;
            }, 1000);
        });
})
</script>
