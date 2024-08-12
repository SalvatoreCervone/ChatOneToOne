<template>
    <div id="listmessage">
        <div id="profile" class="m-2">
            <div>
                <div class="float-right mr-2" style="width: 10px;">
                    <template v-if="props.friend.status">
                        <i class="pi pi-circle-fill text-green-500"></i>
                    </template>
                </div>
                <div class="float-right">
                    <i class="pi pi-times mr-5" @click="chiudichat()"></i>
                </div>
                <div class="flex gap-2">
                    <span>{{ props.friend.name }}</span>
                    <span>{{ props.friend.email }}</span>
                </div>

            </div>
            <Stascrivendo :current_user_id="props.currentUser.id" :friend="friend"></Stascrivendo>
        </div>
        <hr />
        <div id="chat-messages" ref="messagesContainer">
            <template v-if="messages">
                <template v-for="message in messages" :key="message.id">
                    <div v-if="message.sender_id == currentUser.id" class="message right">
                        <div class="bubble bg-blue-500 text-white">
                            {{ message.text }}
                        </div>
                    </div>
                    <div v-else class="message left bg-grey-200">
                        <div class="bubble bg-gray-200">
                            {{ message.text }}
                        </div>
                    </div>
                </template>
            </template>
        </div>
        <hr />
        <div id="sendmessage1" style="height: 50px">
            <InputText :modelValue="newMessage" @update:modelValue="newMessage = $event" @keydown="sendTypingEvent"
                @keyup.enter="sendMessage" placeholder="Scrivi un messaggio..."
                class="flex-1 px-2 py-1 border rounded-lg">
            </InputText>

            <Button icon="pi pi-sign-in" @click="sendMessage" class="px-4 py-1 ml-2 text-white bg-blue-500 rounded-lg">
            </Button>
        </div>
    </div>
</template>
<script setup>
import { ref, defineProps, watch, onMounted, nextTick, defineEmits } from 'vue'
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Stascrivendo from "./Stascrivendo.vue";
import moment from 'moment'
const props = defineProps({
    currentUser: null,
    friend: null
})

const emit = defineEmits(['chiudichat'])
// const chatmessages = ref(false);
const messages = ref([]);
const newMessage = ref("");
const messagesContainer = ref(null);

onMounted(() => {
    Echo.private(`chat.${props.currentUser.id}`).listen(
        "MessageSent",
        (response) => {

            messages.value.push(response.message);
            messagesread(response.message.sender_id)
        }
    );
    messagesread(props.friend.id)
    getmessages(props.friend.id)
});

watch(
    messages,
    () => {
        nextTick(() => {
            messagesContainer.value.scrollTo({
                top: messagesContainer.value.scrollHeight,
                behavior: "smooth",
            });
        });
    },
    { deep: true }
);

const sendTypingEvent = () => {
    Echo.private(`chat.${props.friend.id}`).whisper("typing", {
        userID: props.currentUser.id,
    });
};

const sendMessage = () => {
    if (newMessage.value.trim() !== "") {
        axios
            .post(`/messages/${props.friend.id}`, {
                message: newMessage.value,
            })
            .then((response) => {
                messages.value.push(response.data);
                newMessage.value = "";
            });
    }
};

function chiudichat() {
    // friendslist.value = true;
    // chatmessages.value = false;
    emit('chiudichat', true);
}

function getmessages(user_id) {
    axios.get(`/messages/${user_id}`).then((response) => {
        messages.value = response.data;
    });
}
function messagesread(user_id) {

    axios.post('/messages/receiver/read', {
        friend_id: user_id,
        readtime: moment().format('YYYY-MM-DD kk:mm')
    }).then((response) => {

    });
}
</script>
<style scoped>
#listmessage {
    height: 450px;
}

#profile {
    height: 50px;
}

#profile p.animate {
    margin-top: 97px;
    opacity: 1;
    -webkit-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    -moz-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    -ms-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    -o-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
}

#chat-messages {
    height: 350px;
    overflow-y: scroll;
    overflow-x: hidden;
    padding-right: 10px;
    -webkit-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    -moz-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    -ms-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    -o-transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
    transition: all 200ms cubic-bezier(0, 0.995, 0.99, 1);
}

#chat-messages.animate {
    opacity: 1;
    margin-top: 0;
}

#chat-messages div.message {
    padding: 0 0 3% 5%;
    clear: both;
    margin-bottom: 45px;
}

#chat-messages div.message.right {
    padding: 0 5% 3% 0;
    margin-right: -19px;
    margin-left: 19px;
}

#chat-messages .message img {
    float: left;
    margin-left: -38px;
    border-radius: 50%;
    width: 30px;
    margin-top: 12px;
}

#chat-messages div.message.right img {
    float: right;
    margin-left: 0;
    margin-right: -38px;
}

#chat-messages div.message.right .bubble {
    float: right;
    border-radius: 5px 5px 0px 5px;
    color: rgb(36, 36, 36);
}

.message .bubble {
    /* background: #f0f4f7; */
    font-size: 13px;
    font-weight: 600;
    padding: 12px 13px;
    border-radius: 5px 5px 5px 0px;
    color: #8495a3;
    position: relative;
    float: left;
}



.bubble .corner {
    background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/bubble-corner.png") 0 0 no-repeat;
    position: absolute;
    width: 7px;
    height: 7px;
    left: -5px;
    bottom: 0;
}

div.message.right .corner {
    background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/bubble-cornerR.png") 0 0 no-repeat;
    left: auto;
    right: -5px;
}

.bubble span {
    /* color: #aab8c2; */
    font-size: 11px;
    position: absolute;
    right: 0;
    bottom: -22px;
}
</style>
