<script setup>
import Video from "@/Components/svg/icons/video.vue";
import WhiteArrowLeft from "@/Components/svg/icons/whiteArrowLeft.vue";
import SmileFace from "@/Components/svg/icons/smileFace.vue";
import {defineAsyncComponent, ref, watch} from "vue";
import axios from "axios";
import liveChatStore from "@/stores/liveChatStore.js";

const EmojiPicker = defineAsyncComponent(() => import('vue3-emoji-picker'))

const content = ref('');
const isDisable = ref(false);
const showEmojiPicker = ref(false);

const domainRegex1 = /\b([a-z0-9-]+\.)+[a-z]{2,}\b/gi;
const domainRegex2 = /\b([a-z0-9-]+\.)+[a-z]{3,}\b/gi;

const sendMessage = () => {
    const myTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    showEmojiPicker.value = false;
    if (isDisable.value) return;

    isDisable.value = true;
    axios.post(route('live-chat.send-message'), {
        content: content.value,
        timezone: myTimezone
    }).then(response => {
        content.value = "";
        isDisable.value = false;
        liveChatStore.appendMessage(response.data.message);
    }).catch(error => {
        console.log(error.response.data);
    });
}

watch(content, (newValue) => {
    isDisable.value = newValue.trim().includes('://')
        || newValue.trim().includes('http')
        || newValue.trim().includes('https')
        || domainRegex1.test(newValue.trim())
        || domainRegex2.test(newValue.trim())
        || newValue.length === 0;
}, {immediate: true});
</script>

<template>
    <div
        class="bg-[#181818] w-full px-reply  flex items-center justify-between gap-5 relative">
        <div v-show="showEmojiPicker" class="absolute bottom-[70px] left-[20px] drop-shadow-[0_0_10px_#ff3a44]">
            <EmojiPicker
                theme="dark"
                :native="true"
                :disable-skin-tones="true"
                :display-recent="true"
                :static-texts="{
                        placeholder: 'Search',
                    }"
                @select="(emoji) => content += emoji.i"
            />
        </div>
        <smile-face class="cursor-pointer" @click="showEmojiPicker = !showEmojiPicker"/>
        <input type="text" v-model="content" @keyup.enter="sendMessage"
               class="w-full bg-transparent placeholder-secondary focus:ring-0 pl-0"
               placeholder="Reply ..."  maxlength="1000">
        <div class="space-x-[15px] flex items-center">
            <!-- <v-btn :icon="Video" color="transparent"/> -->
            <v-btn @click="sendMessage" :disabled="isDisable" icon size="40px" color="primaryDark" class="p-2"
                   rounded="pill">
                <white-arrow-left/>
            </v-btn>
        </div>
    </div>
</template>

<style scoped>

</style>
