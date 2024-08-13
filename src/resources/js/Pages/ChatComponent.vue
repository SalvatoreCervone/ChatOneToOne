<template>
    <div id="chatbox" :class="{ 'altezza0': iconizza }">
        <div id="chatmenu" class="grid grid-cols-3 grid-rows-1 text-center">
            <div :class="[{ 'selezionato': friendslistchat }]" title="Le tue chat">

                <i class="pi pi-inbox" @click="chiudichat"></i>
            </div>
            <div :class="[{ 'selezionato': friendslist }]" title="Ricerca utente">

                <i class="pi pi-users" @click="ricercautenti"></i>
            </div>


            <!-- <DropdownLink :href="route('logout')" method="post" as="button">
                Log Out
            </DropdownLink> -->
            <div>

                <i class="pi pi-minus-circle text-yellow-500" v-if="!iconizza" @click="iconizzachat"></i>
                <i class="pi pi-arrow-circle-up text-green-500" v-if="iconizza" @click="iconizzachat"></i>
            </div>
        </div>
        <hr />
        <div v-if="!iconizza">
            <FriendslistChat v-if="props.currentUser" :open="friendslistchat" v-show="friendslistchat"
                :currentUser="props.currentUser" @user="userselected">
            </FriendslistChat>
            <Friendslist v-if="props.currentUser" :open="friendslist" v-show="friendslist"
                :currentUser="props.currentUser" @user="userselected">
            </Friendslist>

            <ChatMessage v-if="friend && chatmessages" v-show="chatmessages" @chiudichat="chiudichat" :friend="friend"
                :currentUser="currentUser" id="chatview"></ChatMessage>
        </div>

    </div>
</template>

<script setup>
import Friendslist from "./Friendslist.vue";
import FriendslistChat from "./FriendslistChat.vue";
import ChatMessage from "./ChatMessage.vue"

import { ref } from "vue";

const props = defineProps({
    currentUser: {
        type: Object,
        required: true,
        default: null
    },
});

const friendslist = ref(false);
const friendslistchat = ref(true);
const chatmessages = ref(false);
const friend = ref(null);
const iconizza = ref(false);


function userselected(val) {
    friend.value = val;
    friendslist.value = false;
    friendslistchat.value = false;
    chatmessages.value = true;
    // getmessages(val.id);
}

function chiudichat(val) {
    friendslist.value = false;
    friendslistchat.value = true;
    chatmessages.value = false;

}

function iconizzachat() {
    iconizza.value = !iconizza.value
}

function ricercautenti() {
    friendslist.value = true;
    friendslistchat.value = false;
    chatmessages.value = false;
}



</script>
<style scoped>
.selezionato {
    background-color: #a2cddd;

}

#chatbox {
    height: 500px;
    width: 400px;
    border: 1px solid rgb(221, 221, 221);

}

#chatmenu {
    height: 50px;

}

#chatmenu div {
    display: grid;

}

#chatmenu i {
    align-self: center;
}



.altezza0 {
    height: 50px !important;
}
</style>
