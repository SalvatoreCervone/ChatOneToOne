<template>
    <div id="chatbox"  class="max-h-screen">
        <div class="grid grid-cols-3 grid-rows-1 text-center m-5">

            <i class="pi pi-users"></i>
            <i class="pi pi-inbox"></i>
            <DropdownLink :href="route('logout')" method="post" as="button">
                Log Out
            </DropdownLink>
        </div>
        <hr />
        <Friendslist class="max-h-fit" v-if="props.currentUser" v-show="friendslist" :currentUser="props.currentUser"
            @user="userselected">
        </Friendslist>


        <ChatMessage v-if="friend" v-show="chatmessages" style="height: 50lvh;" class="max-h-fit" @chiudichat="chiudichat" :friend="friend"
            :currentUser="currentUser"  id="chatview"></ChatMessage>

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

</script>
