<script setup>

import Person from "@/Components/svg/icons/person.vue";
import {defineAsyncComponent, onBeforeUnmount, onMounted, ref} from "vue";
import img1 from "@/images/cards/img1.png";
import img3 from "@/images/cards/img3.png";
import PrimaryCard from "@/Components/PrimaryCard.vue";
import liveChatStore from "@/stores/liveChatStore.js";
import axios from "axios";
import MessageList from "@/Pages/LiveChat/Partials/MessageList.vue";
import {usePage} from "@inertiajs/vue3";


const videoPanel = defineAsyncComponent(() => import('@/Pages/LiveChat/Partials/VideoPanel.vue'))
const InputArea = defineAsyncComponent(() => import('@/Pages/LiveChat/Partials/InputArea.vue'))
const LiveChatHeader = defineAsyncComponent(() => import('@/Pages/LiveChat/Partials/LiveChatHeader.vue'))


const user = usePage().props.auth.user;

const mediaFile = [
    {
        type: 'video',
        id: 1,
        card_thumbnail: img1,
        duration: '6',
        latest: true,
        title: 'i Took sneako Lalala',
        humans_published_at: '4 mins ago',
        user: {
            display_name: 'Arabuncut'
        }
    },
    {
        type: 'series',
        id: 2,
        card_thumbnail: img3,
        duration: '1122',
        latest: false,
        title: 'i Took sneako in Warzone',
        humans_published_at: '4 days ago',
        user: {
            display_name: 'Arabuncut'
        },
        video_count: 5,
    }
]

const scrollableRef = ref(null);
const viewportHeight = ref(800)
const nextPageUrl = ref(null)
const isLastPage = ref(false)
const loadingMessages = ref(false)
const platformUsersCount = ref(null)

const updateViewportHeight = () => {
    if (window.innerHeight < 740) {
        viewportHeight.value = window.innerHeight - 72
    }
}

const fetchMessages = async () => {
    const myTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    loadingMessages.value = true
    await axios.get(nextPageUrl.value ?? route('live-chat.messages'),{
        params: {
            timezone: myTimezone
        }
    }).then(response => {
        liveChatStore.appendMessages(response.data.messages.data)
        nextPageUrl.value = response.data.messages.next_page_url
        isLastPage.value = !response.data.messages.next_page_url
        loadingMessages.value = false
    }).catch(error => {
        console.log(error.response.data);
    });
}

const handleContainerScroll = async (event) => {
    const target = event.target;
    const preserveScroll = target.scrollTop;

    if (!loadingMessages.value) {
        if ((target.scrollTop + (target.scrollHeight - target.offsetHeight)) < 50 && !isLastPage.value) {
            await fetchMessages()
            target.scrollTop = preserveScroll;
        }
    }
}

const joinChannel = () => {
    Echo.private(`User.${user.id}`)
        .listen('.MessageSent', (event) => {
            console.log('listing')
            liveChatStore.appendMessage(event.message)
        });
}

const leaveChannel = () => {
    Echo.leave(`User.${user.id}`)
}

const setContainerHeight = () => {
    const element = scrollableRef.value;
    element.scrollTop = element.scrollHeight;
}

const fetchPlatformUsersCount = () => {
    axios.get(route('live-chat.platform-users-count')).then(response => {
        platformUsersCount.value = response.data.usersCount
    }).catch(error => {
        console.log(error.response.data);
    });
}

const handleKeydown = (event) => {
    if (event.key === 'Escape' && liveChatStore.isLiveChatOpen) {
        liveChatStore.toggleLiveChat();
    }
}

onMounted(() => {
    fetchPlatformUsersCount();
    setContainerHeight();
    fetchMessages();
    joinChannel();

    window.addEventListener('resize', updateViewportHeight)
    window.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
    leaveChannel()

    window.removeEventListener('resize', updateViewportHeight)
    window.removeEventListener('keydown', handleKeydown)
})
</script>

<template>
    <div class="mx-auto max-w-[1883px] w-full flex fixed right-0 z-40 top-[72px] md:top-[94px] left-0">
        <div class="LiveChat ml-auto overflow-hidden top-[98px] z-30 bg-black"
             :class="{ 'expand': liveChatStore.isLiveChatOpen }">
            <div class="relative">
                <!-- <div :style="{ height: viewportHeight + 'px' }" -->
                <div
                     class="max-h-[988px] h-[88vh] flex flex-col justify-between items-center border-r border-black">
                    <LiveChatHeader/>
                    <!-- <div v-if="platformUsersCount" class="sticky top-0 rounded-full py-3 w-full">
                                <span
                                    class="max-w-[64px] h-[28px] w-full float-end flex items-center gap-[2px] px-3 py-1 mr-6 rounded-full border border-primary bg-[#2f2e30] fs-16 lh-20 fm-medium">
                                    <person/> {{ platformUsersCount }}
                                </span>
                    </div> -->

                    <div
                        class="h-full w-full overflow-auto px-30px pb-3 space-y-3 relative flex flex-col-reverse "
                        ref="scrollableRef" @scroll="handleContainerScroll">


                        <!--              Message-->
                        <MessageList/>

                        <!--              Video-->

                        <div class="hidden max-w-[338px]  items-start gap-[10px]">
                            <img src="../../images/heroImg.png" alt="placeholder Image" class="size-10 rounded-full">

                            <div class="space-y-[5px]">
                                <span class="mb-10">James</span>
                                <div class="relative">
                                    <primary-card v-for="item in mediaFile" :item/>
                                </div>
                            </div>

                        </div>
                    </div>
                    <InputArea/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>


.sendFile {
    transition: visibility 400ms, width 500ms;
}

.LiveChat {
    width: 100%;
    max-width: 525px;
    border-radius: 30px 0 0 30px;
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 500ms;
}

@media (max-width: 768px) {
    .LiveChat {
        border-radius: 0;
    }
}

.LiveChat > * {
    overflow: hidden;
}

.expand {
    grid-template-rows: 1fr;
}

span {
    font-size: 16px;
    font-weight: 500;
    line-height: 15px;
}

.gradiant {
    background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 142.56%);
    border-radius: 0 10px 10px 10px;
}

.message {
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
}

.overlay {
    position: absolute;
    z-index: 30;
    bottom: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(to top, black, transparent);
}


/*
scrollbar
*/

/* For Webkit-based browsers like Chrome, Safari, and Edge */
::-webkit-scrollbar {
    width: 2x; /* width of the scrollbar */
}

::-webkit-scrollbar-track {
    background-color: black; /* track (background) color */
}

::-webkit-scrollbar-thumb {
    background-color: black; /* thumb (scroll handle) color */
    border-radius: 10px; /* round corners */
    border: 3px solid black; /* creates padding between thumb and track */
}

::-webkit-scrollbar-thumb:hover {
    background-color: black; /* thumb color on hover */
}

/* For Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: black black; /* thumb color and track color */
}
</style>
