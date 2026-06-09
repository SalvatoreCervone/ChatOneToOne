<template>
  <div id="friendslist" v-if="users.length > 0">
    <div id="friends" v-for="user in users" :key="user.id">
      <div class="p-5">
        <div class="flex" @click="aprichat(user)">
          <div class="self-center m-5">
            <span v-if="user.status"
              ><i class="pi pi-circle-fill text-green-500"></i
            ></span>
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
            <NewMessage
              :unread-count="user.unread_count"
            ></NewMessage>
          </div>
        </div>
        <Stascrivendo
          :current_user_id="props.currentUser.id"
          :friend="user"
        ></Stascrivendo>
      </div>
      <hr />
    </div>
  </div>
  <div v-else>
    <template v-if="searching"> Caricamento... </template>
    <template v-else> Nessuna chat presente </template>
  </div>
</template>
<script setup>
import { ref, onMounted, defineEmits, defineProps, watch } from "vue";
import Stascrivendo from "./Stascrivendo.vue";
import NewMessage from "./NewMessage.vue";
const emit = defineEmits(["user"]);
const users = ref([]);
const searching = ref(false);

const props = defineProps({
  currentUser: { type: Object, required: true },
  open: { type: Boolean, default: false },
  online: { type: Array, default: [] },
});

watch(
  () => props.online,
  () => {
    console.log("modificato", props.online);
    checkonlineList(props.online);
  },
  { immediate: true, deep: true }
);

onMounted(() => {
  searching.value = true;
  axios.get("/users/chat").then((data) => {
    users.value = data.data;
    checkonlineList(props.online);
    searching.value = false;
  });

  Echo.private(`chat.${props.currentUser.id}`).listen(
    ".MessageSent",
    (response) => {
      const user = users.value.find(u => u.id == response.message.sender_id);
      if (user) {
        user.unread_count = (user.unread_count || 0) + 1;
      }
    }
  );
});

function aprichat(user) {
  emit("user", user);
}

function checkonlineList(usersonline) {
  users.value.map(function (user) {
    if (usersonline.find((useronline) => user.id == useronline.id)) {
      user.status = true;
    } else {
      user.status = false;
    }
    return user;
  });
}
</script>
<style scoped>
#friendslist {
  overflow-y: scroll;
  height: 450px;
}
</style>
