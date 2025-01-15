<script setup>
import {computed, defineAsyncComponent, ref} from "vue";
// images
import NavLink from "@/Components/NavLink.vue";
import {Link, usePage} from "@inertiajs/vue3";
import Cross from "@/Components/svg/icons/cross.vue";
import FormCard from "@/Components/FormCard.vue";
import liveChatStore from "@/stores/liveChatStore.js";


// svgs
const home = defineAsyncComponent(() => import('@/Components/svg/icons/home.vue'));
const shorts = defineAsyncComponent(() => import('@/Components/svg/icons/shorts.vue'));
const series = defineAsyncComponent(() => import('@/Components/svg/icons/series.vue'));
const videos = defineAsyncComponent(() => import('@/Components/svg/icons/videos.vue'));
const community = defineAsyncComponent(() => import('@/Components/svg/icons/community.vue'));
const bell = defineAsyncComponent(() => import('@/Components/svg/icons/bell.vue'));
const arrowUp = defineAsyncComponent(() => import('@/Components/svg/icons/arrowUpSvg.vue'));
const search = defineAsyncComponent(() => import('@/Components/svg/icons/search.vue'));
const dropdownArrow = defineAsyncComponent(() => import('@/Components/svg/icons/dropdownArrow.vue'));
const Logo = defineAsyncComponent(() => import('@/Components/svg/Logo.vue'));
const menuIcon = defineAsyncComponent(() => import('@/Components/svg/icons/menuIcon.vue'));
const DropdownArrow = defineAsyncComponent(() => import('@/Components/svg/icons/dropdownArrow.vue'));
const Settings = defineAsyncComponent(() => import('@/Components/svg/icons/settings.vue'));
const Exit = defineAsyncComponent(() => import('@/Components/svg/icons/exit.vue'));

const user = computed(() => usePage().props.auth.user);

const NavLinks = [
    {
        label: 'Home',
        icon: home,
        url: route('home'),
        active: route().current('home')
    },
    {
        label: 'Shorts',
        icon: shorts,
        url: route('shorts.page'),
        active: route().current('shorts.*')
    },
    {
        label: 'Series',
        icon: series,
        url: route('series.index'),
        active: route().current('series.*')
    },
    {
        label: 'Videos',
        icon: videos,
        url: route('videos'),
        active: route().current('videos')
    },
    {
        label: 'Community',
        icon: community,
        url: route('community'),
        active: route().current('community')
    },
];

let menu = [
    {
        icon: Settings,
        label: 'Profile settings',
        url: route('profile.show'),
        method: 'get',
        active: route().current('profile.show'),
        outside: false,
    },
    {
        icon: Settings,
        label: 'Manage Subscription',
        url: 'https://apps.apple.com/account/subscriptions',
        method: 'get',
        outside: true,
    },
]

if (!usePage().props.auth.apple_user) {
    menu = [
        {
            icon: Settings,
            label: 'Profile settings',
            url: route('profile.show'),
            method: 'get',
            active: route().current('profile.show'),
            outside: false,
        },
        {
            icon: Settings,
            label: 'Manage Subscription',
            url: usePage().props.auth.manage_subscription_url,
            method: 'get',
            outside: true,
        },
    ]
}

const isMenuOpen = ref(false);

const sideNavOpen = ref(false)
const toggleSideNav = () => {
    sideNavOpen.value = !sideNavOpen.value;
}

</script>

<template>
    <div class="max-w-[1930px] mx-auto px-[13px] md:px-[30px] w-full">
        <div class="bg-transparent min-h-[46px] flex justify-between items-center md:gap-4 w-full">

            <!--            left side-->
            <div class="flex items-center justify-between max-nav w-full gap-1 lg:gap-[77px]">
                <Logo/>
                <div class="flex items-center justify-end w-full gap-1 lg:gap-6">
                    <NavLink v-for="(link, index) in NavLinks" :key="index" :link="link"/>
                </div>
            </div>

            <!--            Right side-->

            <div class="flex items-center gap-[10px] ">

                <a target="_blank" href="https://taboo.tv/updates" class="mr-3">
                    <div class="d-flex justify-center flex-column">
                        <div class="my-auto ms-2">Releases</div>
                    </div>
                </a>

                <v-btn
                    @click="liveChatStore.toggleLiveChat()"
                    rounded="pill"
                    style="border: 2px solid var(--primary-dark-color)"
                    width="105px"
                    class="  fs-14 lh-18 fm-medium transition-all duration-300"
                    :class="liveChatStore.isLiveChatOpen ? 'bg-transparent': 'bg-primaryDark'">
                    Live Chat
                </v-btn>



                <Link :href="route('notifications')">
                    <div :class="route().current('notifications') && 'active-nav-btn'"
                         class="grid place-items-center size-[32px] cursor-pointer rounded-full hover:bg-white/20 duration-300 LiveChat">
                        <bell/>
                    </div>
                </Link>

                <Link :href="route('searches')">
                    <div :class="route().current('searches') && 'active-nav-btn'"
                         class="grid place-items-center size-[32px] cursor-pointer rounded-full hover:bg-white/20 duration-300 LiveChat">
                        <search/>
                    </div>
                </Link>


                <!--                profile-->
                <div class="text-center hidden lg:inline">
                    <v-menu v-model="isMenuOpen">
                        <template v-slot:activator="{ props }">
                            <v-btn class="min-h-[46px] p-0 flex items-center shadow-none" style="padding: 0 !important;"
                                   color="transparent"
                                   dark
                                   v-bind="props"
                                   elevation="0"
                            >
                                <img :src="user.small_dp" alt="placeholder Image"
                                     class="size-[46px] max-w-[46px] rounded-full mr-2">
                                <dropdown-arrow class="transition-all duration-300 min-w-[14px]"
                                                :class="{'rotate-180': isMenuOpen}"/>
                            </v-btn>
                        </template>

                        <v-list class="px-2 border min-w-[170px] rounded-lg mt-6">
                            <div v-for="(link, key) in menu" :key>

                                <Link v-if="link.outside === false" :href="link.url" :method="link.method">
                                    <v-list-item
                                        rounded="lg"
                                        base-color="secondary"
                                        density="compact"
                                    >
                                <span class="flex gap-2 items-center">
                                    <component :is="link.icon"
                                               :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                {{ link.label }}</span>
                                    </v-list-item>
                                </Link>

                                <a v-else :href="link.url" target="_blank">
                                    <v-list-item
                                        rounded="lg"
                                        base-color="secondary"
                                        density="compact"
                                    >
                                <span class="flex gap-2 items-center">
                                    <component :is="link.icon"
                                               :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                {{ link.label }}</span>
                                    </v-list-item>
                                </a>
                            </div>

                            <v-dialog max-width="659">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-list-item v-bind="activatorProps"
                                                 rounded="lg"
                                                 base-color="secondary"
                                                 density="compact"
                                    >
                                        <span class="flex gap-2 items-center"><Exit/> Logout</span></v-list-item>
                                </template>

                                <template v-slot:default="{ isActive }">
                                    <div class="p-5 md:py-[44px] md:px-[67px] bg-[#0A070B]/70 rounded-[20px] w-full">
                                        <h3 class="font-extrabold text-[24px]">Logout</h3>
                                        <p class="text-secondary mt-3 text-[16px]">Are you sure you would like to
                                            logout</p>

                                        <div class="flex justify-between gap-2 mt-6">
                                            <v-btn @click="isActive.value = false" rounded="pill" height="48px"
                                                   width="50%" max-width="257px"
                                                   base-color="accent" style="font-size: 18px" class="">
                                                cancel
                                            </v-btn>
                                            <Link class="w-full" :href="route('logout')" method="post">
                                                <v-btn rounded="pill" height="48px" width="100%" max-width="257px"
                                                       base-color="primaryDark" style="font-size: 18px" class="">
                                                    Logout
                                                </v-btn>
                                            </Link>
                                        </div>
                                    </div>

                                </template>
                            </v-dialog>
                        </v-list>
                    </v-menu>
                </div>


                <v-overlay v-model="sideNavOpen" location="right" class="bg-transparent opacity-1">
                    <template #activator="{ isActive, props }">
                        <div class="lg:hidden" v-bind="props">
                            <menuIcon/>
                        </div>
                    </template>
                    <div class="card min-w-[240px] border border-white/10 h-screen opacity-95 w-full">
                        <v-btn @click="toggleSideNav" :icon="Cross" class="ring-0"
                               style="position:absolute; right: 2px; top: 0"></v-btn>
                        <ul class="space-y-[15px]">
                            <li v-for="(link, index) in NavLinks" :key="index" class="p-2 rounded-lg"
                                :class="link.active ?? 'link-background'">
                                <Link :href="link.url"
                                      :class=" link.active ? 'text-white' : 'text-secondary'">
                                    <span class="flex text-[14px] leading-[14px]  items-center gap-2 w-full">
                                        <component :is="link.icon" :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                        {{ link.label }}
                                    </span>
                                </Link>
                            </li>
                        </ul>
                        <ul class="space-y-[15px] text-secondary">
                            <li v-for="(link, index) in menu" :key="index" class="p-2 rounded-lg"
                                :class="link.active ?? 'link-background'">
                                <Link v-if="link.outside === false" :href="link.url"
                                      :class=" link.active ? 'text-white' : 'text-secondary'">
                                    <span class="flex text-[14px] leading-[14px] items-center gap-2 w-full">
                                        <component :is="link.icon" :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                        {{ link.label }}
                                    </span>
                                </Link>
                                <a v-else :href="link.url" target="_blank">
                                    <span class="flex text-[14px] leading-[14px] items-center gap-2 w-full">
                                        <component :is="link.icon" :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                        {{ link.label }}
                                    </span>
                                </a>
                            </li>
                            <li class="p-2 rounded-lg">
                                <Link :href="route('logout')" method="post" class="w-full">
                                    <span class="text-[14px] leaidng-[14px]">
                                        <span class="flex gap-2 items-center"><Exit/> Logout</span></span>
                                </Link>
                            </li>

                        </ul>
                    </div>
                </v-overlay>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media (max-width: 1023px) {
    .LiveChat {
        display: none;
    }
}

h6 {
    font-size: 16px;
    font-weight: 400;
    line-height: 20px;
}

li:hover {
    background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 142.56%);
    color: white !important;
}

.card {
    padding: 70px 20px;
    border-radius: 12px 0 0 12px;
    display: flex !important;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
    backdrop-filter: blur(30px);
    box-shadow: -2px 3px 11px 0 #00000075;
    background-color: rgba(255, 255, 255, 10%);
}

.active-nav-btn {
    border: 1px solid #AB001385;
    background: linear-gradient(0deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 100%);
}
</style>

<style>
.v-overlay__scrim {
    backdrop-filter: blur(100px) !important;
}
</style>
