<template>
    <div id="chatbox" :class="{ 'altezza0': iconizza }">
        <div class="grid grid-cols-4 grid-rows-1 text-center m-5">

            <i class="pi pi-users"></i>
            <i class="pi pi-inbox"></i>
            <DropdownLink :href="route('logout')" method="post" as="button">
                Log Out
            </DropdownLink>
            <i class="pi pi-circle-fill color-yellow-500" @click="iconizzachat"></i>
        </div>
        <hr />
        <div v-if="!iconizza">
            <Friendslist class="max-h-fit" v-if="props.currentUser" v-show="friendslist"
                :currentUser="props.currentUser" @user="userselected">
            </Friendslist>

            <ChatMessage v-if="friend" v-show="chatmessages" style="height: 50lvh;" class="max-h-fit"
                @chiudichat="chiudichat" :friend="friend" :currentUser="currentUser" id="chatview"></ChatMessage>
        </div>

    </div>
</template>

<script setup>
import Friendslist from "./Friendslist.vue";
import ChatMessage from "./ChatMessage.vue"
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref } from "vue";

const props = defineProps({
    currentUser: {
        type: Object,
        required: true,
        default: null
    },
});

const friendslist = ref(true);
const chatmessages = ref(false);
const friend = ref(null);
const iconizza = ref(false);


function userselected(val) {
    friend.value = val;
    friendslist.value = false;
    chatmessages.value = true;
    // getmessages(val.id);
}

function chiudichat() {
    friendslist.value = true;
    chatmessages.value = false;

}

function iconizzachat() {
    iconizza.value = !iconizza.value
}

</script>
<style scoped>
#chatbox {
    height: 500px;
    width: 400px;

}

.altezza0 {
    height: 0px;
}
</style>
