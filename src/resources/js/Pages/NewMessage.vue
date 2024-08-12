<template>

    <i v-if="newmessages" class="pi pi-comment"></i>
</template>
<script setup>
import { ref, defineProps, watch } from 'vue'
const props = defineProps({
    friend_id: null,
    newmessages: { type: Number, default: 0 },
    force: false

})

const newmessages = ref(false)



watch(() => props.friend_id, () => {
    messagestoread(props.friend_id);

}, {
    immediate: true
})

watch(() => props.force, () => {
    check()
}, {
    immediate: true
})

watch(() => props.newmessages, () => {
    check()
}, {
    immediate: true
})

function check() {
    newmessages.value = props.newmessages == props.friend_id
}

function messagestoread(user_id) {

    axios.get('/messages/receiver/toread/' + user_id).then(data => {
        if (data.data > 0) {

            newmessages.value = user_id
        } else {

            newmessages.value = 0
        }
    })


}
</script>
