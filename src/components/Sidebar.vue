<template>
    <div class="sidebar">
        <h2 class="text-lg font-semibold mb-4">Usuários Online</h2>
        <ul>
            <li v-for="user in onlineUsers" :key="user.id"
                class="flex items-center justify-between p-2 border-b border-gray-600">
                <span>{{ user.name }}</span>
                <div class="flex gap-2">
                    <button v-if="incomingCallUserId !== user.id" @click="callUser(user.id)"
                        class="bg-blue-500 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M6.62 10.79a15.72 15.72 0 006.59 6.59l2.2-2.2a1 1 0 011-.24 11.64 11.64 0 003.65.58 1 1 0 011 1v3.79a1 1 0 01-1 1A19 19 0 013 4a1 1 0 011-1h3.79a1 1 0 011 1 11.64 11.64 0 00.58 3.65 1 1 0 01-.24 1z" />
                        </svg>
                    </button>
                    <div v-if="incomingCallUserId === user.id" class="flex gap-2">
                        <button @click="acceptCall(user.id)" class="bg-green-500 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M6.62 10.79a15.72 15.72 0 006.59 6.59l2.2-2.2a1 1 0 011-.24 11.64 11.64 0 003.65.58 1 1 0 011 1v3.79a1 1 0 01-1 1A19 19 0 013 4a1 1 0 011-1h3.79a1 1 0 011 1 11.64 11.64 0 00.58 3.65 1 1 0 01-.24 1z" />
                            </svg>
                        </button>
                        <button @click="rejectCall(user.id)" class="bg-red-500 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white rotate-90"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.62 10.79a15.72 15.72 0 006.59 6.59l2.2-2.2a1 1 0 011-.24 11.64 11.64 0 003.65.58 1 1 0 011 1v3.79a1 1 0 01-1 1A19 19 0 013 4a1 1 0 011-1h3.79a1 1 0 011 1 11.64 11.64 0 00.58 3.65 1 1 0 01-.24 1z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </li>
        </ul>
        <CallPopup v-if="inCall" :username="activeCall.username" @endCall="endCall" />
    </div>
</template>

<script>
import { get, post } from "../http/auth-api";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import CallPopup from "./CallPopup.vue";

export default {
    components: {
        CallPopup,
    },

    data() {
        return {
            onlineUsers: [],
            incomingCallUserId: null,
            myId: localStorage.getItem("my_id"),
            activeCall: null,
            inCall: false,
        };
    },
    methods: {
        async fetchOnlineUsers() {
            try {
                const response = await get("/users/get-online");
                this.onlineUsers = response.data;
            } catch (error) {
                console.error("Erro ao buscar usuários online", error);
            }
        },
        listenForCalls() {
            window.Pusher = Pusher;
            window.echo = new Echo({
                broadcaster: "pusher",
                key: "03432b80dd09a95b9a39",
                cluster: "us2",
                forceTLS: true
            });

            window.echo.channel("calls")
                .listen(`.IncomingCall_${this.myId}`, (event) => {
                    console.log("Recebendo chamada:", event);
                    this.incomingCallUserId = event.caller_id;
                });

            window.echo.channel("calls")
                .listen(`.ResponseCall_${this.myId}`, (event) => {
                    console.log("Recebendo resposta de chamada:", event);
                    this.incomingCallUserId = null;
                    if (!event.accepted) {
                        toast.error("Chamada recusada pelo usuário.");
                        return;
                    }

                    this.inCall = true;
                    this.activeCall = {
                        username: event.username,
                    };
                });
        },
        async callUser(userId) {
            console.log("Ligando ", userId);
            try {
                await post("/calls/request", { 'user_id': userId });
            } catch (error) {
                console.error("Erro ao solicitar chamada", error);
            }
        },
        async acceptCall(userId) {
            console.log("Chamada aceita de", userId);
            this.incomingCallUserId = null;
            try {
                let response = await post("/calls/accept", { 'caller_id': userId, 'receiver_id': this.myId });
                this.inCall = true;
                this.activeCall = {
                    username: response.username,
                };
            } catch (error) {
                console.error("Erro ao aceitar chamada", error);
            }
        },
        async rejectCall(userId) {
            console.log("Chamada recusada de", userId);
            this.incomingCallUserId = null;
            try {
                await post("/calls/reject", { 'caller_id': userId, 'receiver_id': this.myId });
            } catch (error) {
                console.error("Erro ao recusar chamada", error);
            }
        },
        endCall() {
            this.inCall = false;
            this.activeCallUser = "";
        }

    },
    mounted() {
        this.fetchOnlineUsers();
        this.listenForCalls();
    }
};
</script>

<style scoped>
.sidebar {
    position: fixed;
    right: 0;
    top: 44;
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 20px;
    height: 100vh;
    overflow-y: hidden;
    z-index: 100;
}
</style>
