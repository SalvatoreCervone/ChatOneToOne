<template>

    <i v-if="newmessages" class="pi pi-comment"></i>
</template>
<script setup>
import { ref, defineProps, watch } from 'vue'
const props = defineProps({
    friend_id: null,
    newmessages: { type: Boolean, default: false },
    force: false

})

const newmessages = ref(false)



watch(() => props.friend_id, () => {
    messagestoread(props.friend_id);

}, {
    immediate: true
})

watch(() => props.force, () => {
    newmessages.value = props.newmessages
}, {
    immediate: true
})

watch(() => props.newmessages, () => {
    newmessages.value = props.newmessages
}, {
    immediate: true
})

function messagestoread(user_id) {

    axios.get('/messages/receiver/toread/' + user_id).then(data => {
        if (data.data > 0) {

            newmessages.value = true
        } else {

            newmessages.value = false
        }
    })


}
</script>
