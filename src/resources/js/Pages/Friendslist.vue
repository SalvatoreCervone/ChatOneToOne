<template>
    <div id="friendslist">
        <div id="friends" v-for="user in users" :key="user.id">
            <div class="p-5">
                <div class="flex" @click="aprichat(user)">
                    <!-- <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/1_copy.jpg" /> -->
                    <div class="self-center m-5">
                        <span v-if="user.status"><i class="pi pi-circle-fill text-green-500"></i></span>
                        <span v-else><i class="ml-3"></i></span>
                    </div>
                    <div>
                        <div>
                            <strong>{{ user.name }}</strong>
                        </div>
                        <div>
                            <span>{{ user.email }}</span>
                        </div>

                    </div>
                    <div class="self-center ml-5">


                        <NewMessage :force="force" :newmessages="setnewmessage" :friend_id="user.id"></NewMessage>
                        <!-- <i v-if="nuovomessaggio(user.id)" class="pi pi-comment"></i> -->
                    </div>
                </div>
                <Stascrivendo :current_user_id="props.currentUser.id" :friend="user"></Stascrivendo>
            </div>
            <hr />
        </div>
        <!-- <div id="search">
                <input type="text" id="searchfield" value="Ricerca contatto..." />
            </div> -->
    </div>
</template>
<script setup>
import { ref, onMounted, defineEmits, defineProps } from "vue";
import Stascrivendo from "./Stascrivendo.vue";
import NewMessage from "./NewMessage.vue";
const emit = defineEmits(["user"]);
const users = ref([]);
const newmessage = ref([]);
const setnewmessage = ref(0);
const force = ref(false);

const props = defineProps({
    currentUser: { type: Object, required: true },
    open: { type: Boolean, default: false }
});

function nuovomessaggio(user_id) {
    if (props.open) {

        setnewmessage.value = newmessage.value.includes(user_id) ? user_id : 0;
    } else {
        setnewmessage.value = 0;
    }
};



onMounted(() => {

    axios.get("/users").then((data) => {
        users.value = data.data;
    });



    Echo.join('users')
        .here((usersonline) => {
            console.log('here', usersonline)
            checkonlineList(usersonline)
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
        })



    Echo.private(`chat.${props.currentUser.id}`)
        .listen("MessageSent", (response) => {

            newmessage.value.push(response.message.sender_id);
            nuovomessaggio(response.message.sender_id)

        });

});




function aprichat(user) {
    const index = newmessage.value.indexOf(user.id);
    if (index > -1) {
        newmessage.value.splice(index, 1);
    }
    setnewmessage.value = 0
    force.value = true
    emit("user", user);
}

function checkonline(useronline) {
    users.value.map(function (user) {
        // usersonline.find(function (useronline) {

        if (user.id == useronline.id) {
            user.status = true
        } else {
            user.status = false

        }
        // })
    })
}
function checkonlineList(usersonline) {

    users.value.map(function (user) {
        if (usersonline.find((useronline) => user.id == useronline.id)) {

            user.status = true
        } else {

            user.status = false

        }
        return user
    })

}
function checkoffline(usersonline) {
    users.value.map(function (user) {
        // usersonline.find(function (useronline) {

        if (user.id == usersonline.id) {
            user.status = false
        }
        // })
    })
}
</script>
<style scoped>
#friendslist {
    overflow-y: scroll;
    height: 450px
}
</style>
