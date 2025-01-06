<script setup>
import {ref} from "vue";
import Mute from "@/Components/svg/icons/mute.vue";
import AllGood from "@/Components/svg/icons/allGood.vue";
import Warning from "@/Components/svg/icons/warning.vue";
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import Like from "@/Components/svg/icons/like.vue";
import Dislike from "@/Components/svg/icons/dislike.vue";
import CommentsArrow from "@/Components/svg/icons/commentsArrow.vue";
import replySvg from "@/Components/svg/icons/reply.vue";
import Hide from "@/Components/svg/icons/hide.vue";

const props = defineProps({
    reply: Object,
    index: Number
})

const actionMenu = [
    {
        icon: replySvg,
        label: 'Reply',
        url: '#'
    },
    {
        icon: Hide,
        label: 'Hide',
        url: '#'
    },
    {
        icon: Warning,
        label: 'Report',
        url: '#'
    },
]

const isLiked = ref(false);
const isDisliked = ref(false);

function toggleLike() {
    isLiked.value = !isLiked.value;
    if (isLiked.value) {
        isDisliked.value = false;
    }
}

function toggleDislike() {
    isDisliked.value = !isDisliked.value;
    if (isDisliked.value) {
        isLiked.value = false;
    }
}


const loadReply = ref(false)
// const loadMore = ref('hidden')

</script>

<template>
    <div class="w-full space-y-1 my-2">

        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-2 min-w-fit">
                <span class="flex items-start" :class="index !== 0 && 'pl-4'">
                <commentsArrow v-if="index === 0" class="-translate-y-0"/>
                <img :src="reply.user?.dp" alt="hero img" class=" size-[30px] md:size-[48px] rounded-full">
                </span>

                <p class="heading">{{ reply.user?.display_name }}</p>
                <span class="text-[16px] text-secondary">{{ reply.created_at }}</span>
            </div>

            <div class="flex items-center justify-between w-full max-w-[150px] gap-3">
                <div class="cursor-pointer" @click="toggleLike">
                    <like :isActive="isLiked"/>
                </div>
                <div class="cursor-pointer" @click="toggleDislike">
                    <dislike :isActive="isDisliked"/>
                </div>
                <v-menu>
                    <template v-slot:activator="{ props }">
                        <v-btn :icon="ThreeDots" class="min-h-[46px] p-0 flex items-center shadow-none"
                               color="transparent"
                               dark
                               v-bind="props"
                               elevation="0"
                        >
                        </v-btn>
                    </template>

                    <v-list class="px-2 border min-w-[170px] rounded-lg mt-1">
                        <v-list-item
                            v-for="(link, key) in actionMenu" :key
                            rounded="lg"
                            :href="link.url"
                            base-color="secondary"
                            density="compact"
                        >
                                <span class="flex gap-2 items-center">
                                    <component :is="link.icon"
                                               :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                {{ link.label }}</span>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>
        </div>
        <p class="text-[13px] md:text-[16px]">{{ reply.message }}</p>

        <button v-if="reply.replies" @click="loadReply = !loadReply"
                class="text-[13px] md:text-[16px] font-medium text-[#69C9D0] block">{{
                loadReply ? 'Hide' : 'Show Reply'
            }}ddd
        </button>


        <!--        <button v-if="loadMore === 'hidden' && index === 1" @click="loadMore = 'visible'"
                        class="text-[13px] md:text-[16px] font-medium text-[#69C9D0]">Load More Replies
                </button>-->

        <comment-reply v-if="loadReply" :reply="reply.replies"/>
    </div>
</template>

<style scoped>

</style>
