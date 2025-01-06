<script setup>

import liveChatStore from "@/stores/liveChatStore.js";
import {usePage} from "@inertiajs/vue3";

const user = usePage().props.auth.user;

const userName = (_user) => {
    if (_user.id === user.id) {
        return "You";
    } else {
        return  _user.display_name;
    }
}
</script>

<template>
    <TransitionGroup tag="div" name="messageContainer">
        <div v-for="(message, key) in liveChatStore.messages" :key class="flex flex-rev items-start justify-start gap-[10px]">
            <img :src="message?.user?.dp" alt="placeholder Image" class="size-10 rounded-full">

            <div class="flex flex-col gap-1 w-full fs-16 fm-medium fw-500 leading-[normal]">
                <div class="d-flex align-items-center">
                    <div class="my-auto">
                        {{userName(message?.user)}}
                    </div>
                    <img v-if="message?.user?.badge" :src="message?.user?.badge" alt="placeholder Image" class="ms-1 my-auto" style="height: 15px;width: auto">
                </div>
                <p class="w-full gradiant py-[10px] px-[15px] break-all message" v-html="message.content"></p>
                <p class="ml-auto fs-14 fm-book leading-[normal] text-secondary">{{ message.time }}</p>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>
.gradiant {
    background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 142.56%);
    border-radius: 0 10px 10px 10px;
}

.message {
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
}

.messageContainer-move,
.messageContainer-enter-active {
    transition: all 250ms ease;
}

.messageContainer-enter-from {
    opacity: 0;
    transform: translateY(100%);
}
</style>
