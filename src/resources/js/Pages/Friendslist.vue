<template>
    <div id="friendslist">

        <InputText :modelValue="ricercautente_term" @update:modelValue="ricercautente_term = $event"
            class="w-full rounded-none" placeholder="Ricerca utente..." @keyup="ricercautente"></InputText>
        <div id="friends" v-for="user in usersfilter" :key="user.id">
            <div class="p-5">
                <div class="flex" @click="aprichat(user)">
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
                  
                </div>
                <Stascrivendo :current_user_id="props.currentUser.id" :friend="user"></Stascrivendo>
            </div>
            <hr />
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, defineEmits, defineProps, watch } from "vue";

import Stascrivendo from "./Stascrivendo.vue";
import InputText from "primevue/inputtext"
const emit = defineEmits(["user"]);
const users = ref([]);
const usersfilter = ref([]);
const newmessage = ref([]);
const setnewmessage = ref(0);
const force = ref(false);
const ricercautente_term = ref("");

const props = defineProps({
    currentUser: { type: Object, required: true },
    open: { type: Boolean, default: false },
    online: { type: Array, default: [] }
});

watch(() => props.online, () => {

    checkonlineList(props.online)
}, { immediate: true })


onMounted(() => {

    axios.get("/users").then((data) => {
        users.value = data.data;
        usersfilter.value = data.data
        checkonlineList(props.online)
    });
});


function ricercautente() {
    if (ricercautente_term.value.length > 0) {
        let terms = ricercautente_term.value.split(" ");
        terms.map(function (term) {
            if (term.length > 0) {

                let find = users.value.map(function (user) {
                    let nominativo = user.name?.toLowerCase() + " " + user.cognome?.toLowerCase()
                    return nominativo.includes(term.toLowerCase()) ? user : null;
                })
                usersfilter.value = find.filter(n => n)
            }
        })
    } else {
        usersfilter.value = users.value
    }

}

function aprichat(user) {
    // const index = newmessage.value.indexOf(user.id);
    // if (index > -1) {
    //     newmessage.value.splice(index, 1);
    // }
    // setnewmessage.value = 0
    // force.value = true
    
    emit("user", user);
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

</script>
<style scoped>
#friendslist {
    overflow-y: scroll;
    height: 450px
}
</style>
