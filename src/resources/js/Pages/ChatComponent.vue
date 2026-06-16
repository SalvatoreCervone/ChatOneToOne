<template>
    <div id="chatbox" :class="{ 'altezza0': iconizza }">
        <div id="chatmenu" class="grid grid-cols-3 grid-rows-1 text-center">

            <div v-if="messagetoread > 0" :class="[{ 'selezionato': friendslistchat }]" title="Le tue chat">
                <Badge class="absolute left-[85px]">{{ messagetoread }}</Badge>
                <i v-badge="messagetoread" class="pi pi-inbox " @click="chiudichat"></i>
            </div>
            <div v-else :class="[{ 'selezionato': friendslistchat }]" title="Le tue chat">

                <i class="pi pi-inbox " @click="chiudichat"></i>
            </div>
            <div :class="[{ 'selezionato': friendslist }]" title="Ricerca utente">

                <i class="pi pi-users" @click="ricercautenti"></i>
            </div>

            <div>

                <i class="pi pi-minus-circle text-yellow-500" v-if="!iconizza" @click="iconizzachat"></i>
                <i class="pi pi-arrow-circle-up text-green-500" v-if="iconizza" @click="iconizzachat"></i>
            </div>
        </div>
        <hr />
        <div v-if="!iconizza">

            <FriendslistChat v-if="props.currentUser && friendslistchat" :open="friendslistchat" :online="onlineuser"
                :currentUser="props.currentUser" @user="userselected">
            </FriendslistChat>
            <Friendslist v-if="props.currentUser && friendslist" :open="friendslist" :online="onlineuser"
                :currentUser="props.currentUser" @user="userselected">
            </Friendslist>

            <ChatMessage v-if="friend && chatmessages" @chiudichat="chiudichat" @letturaeffettuata="letturaeffettuata"
                :friend="friend" :currentUser="props.currentUser" id="chatview">
            </ChatMessage>
        </div>

    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Friendslist from "./Friendslist.vue";
import FriendslistChat from "./FriendslistChat.vue";
import ChatMessage from "./ChatMessage.vue"
import Badge from "primevue/badge";

const props = defineProps({
    currentUser: {
        type: Object,
        required: true,
        default: null
    },
    chat_closed: {
        type: Boolean,
        default: true
    },
    initialUnreadCount: {
        type: Number,
        default: null
    }
});

const friendslist = ref(false);
const friendslistchat = ref(true);
const chatmessages = ref(false);
const friend = ref(null);
const iconizza = ref(props.chat_closed);
const messagetoread = ref(0);
const onlineuser = ref([]);
const isPrivateSubscribed = ref(false);
const isPresenceSubscribed = ref(false);

onMounted(() => {
    subscribePrivate();
    subscribePresence();

    if (props.initialUnreadCount !== null) {
        messagetoread.value = props.initialUnreadCount;
    } else {
        getmessagetoread();
    }
});

function subscribePrivate() {
    if (isPrivateSubscribed.value || !window.Echo) return;

    window.Echo.private(`chat.${props.currentUser.id}`)
        .listen(".MessageSent", (response) => {
            if (chatmessages.value == false) {
                messagetoread.value += 1;
            }
        });

    isPrivateSubscribed.value = true;
}

function subscribePresence() {
    if (isPresenceSubscribed.value || !window.Echo) return;

    window.Echo.join('users')
        .here((usersonline) => {
            console.log('here', usersonline)
            onlineuser.value = usersonline
        })
        .joining((useronline) => {
            console.log('joining', useronline)
            checkonline(useronline)
        })
        .leaving((useronline) => {
            console.log('leaving', useronline)
            checkoffline(useronline)
        }).error(function (error) {
            console.log(error)
        });

    isPresenceSubscribed.value = true;
}

function unsubscribePresence() {
    if (!isPresenceSubscribed.value || !window.Echo) return;

    window.Echo.leave('users');
    isPresenceSubscribed.value = false;
}

function checkonline(useronline) {
    onlineuser.value.push(useronline)
}

function checkoffline(usersonline) {
    onlineuser.value = onlineuser.value.filter(function (user) {
        return user.id != usersonline.id
    })
}

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

function letturaeffettuata() {
    getmessagetoread()
}

function iconizzachat() {
    iconizza.value = !iconizza.value
}

function ricercautenti() {
    friendslist.value = true;
    friendslistchat.value = false;
    chatmessages.value = false;
}

function getmessagetoread() {
    axios.get('/users/chat/count').then(data => {
        messagetoread.value = data.data
    })
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
