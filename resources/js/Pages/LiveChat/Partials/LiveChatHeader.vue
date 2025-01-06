<script setup>
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import Cross from "@/Components/svg/icons/cross.vue";
import liveChatStore from "@/stores/liveChatStore.js";
import Mute from "@/Components/svg/icons/mute.vue";
import AllGood from "@/Components/svg/icons/allGood.vue";
import Warning from "@/Components/svg/icons/warning.vue";


const actionMenu = [
    {
        icon: Mute,
        label: 'Mute',
        url: '#'
    },
    {
        icon: AllGood,
        label: 'Mark all as read',
        url: '#'
    },
    {
        icon: Warning,
        label: 'Report User',
        url: '#'
    },
]
</script>

<template>
    <div class="w-full overflow-hidden bg-primaryDark  p-chat-header flex justify-between items-center">
        <span class="min-w-fit fs-16 leading-[normal] fm-medium fw-500">Live chat</span>

        <div class="flex items-center gap-5">
            <v-menu>
                <!-- <template v-slot:activator="{ props }">
                    <v-btn :icon="true" class="min-h-[46px] p-0 flex items-center shadow-none"
                           color="transparent"
                           dark
                           v-bind="props"
                           elevation="0"
                    ><ThreeDots color="white" />
                    </v-btn>
                </template> -->

                <v-list class="px-10px border min-w-[185px] w-fit rounded-lg mt-1 space-y-2">
                    <v-list-item
                        v-for="(link, key) in actionMenu" :key
                        rounded="lg"
                        :href="link.url"
                        base-color="secondary"
                        density="compact"
                        class="notification-drop"
                    >
                                <span class="flex gap-2 items-center fs-14 leading-[normal] fm-book fw-400">
                                    <component :is="link.icon"
                                               :color=" true ? '#FFF' : '#9f9f9f'"/>
                                {{ link.label }}</span>
                    </v-list-item>
                </v-list>
            </v-menu>
            <v-btn :icon="Cross" color="transparent" style="box-shadow: none" @click="liveChatStore.toggleLiveChat()">
            </v-btn>
        </div>
    </div>
</template>
