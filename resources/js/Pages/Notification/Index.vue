<script setup>
import Page from "@/Components/Page.vue";
import {Head, Link} from "@inertiajs/vue3";
import Cross from "@/Components/svg/icons/cross.vue";
import NotificationCard from "@/Pages/Notification/Partials/NotificationCard.vue";
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import {usePage} from '@inertiajs/vue3';

const notifications = ref([])

const fetchNotifications = () => {
    axios.get(route('notifications.list')).then(response => {
        notifications.value = response.data.notifications;
    })
}

const unreadNotifications = computed(() => {
    return notifications.value.filter(notification => !notification.has_read);
})

const hasUnreadNotifications = computed(() => {
    return unreadNotifications.value.length > 0;
})

const readNotifications = computed(() => {
    return notifications.value.filter(notification => notification.has_read);
})

const readAll = () => {
    axios.post(route('notifications.read-all')).then(response => {
        fetchNotifications();
    }).catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
};

const handleDestroy = (id) => {
    notifications.value = notifications.value.filter(notification => notification.id !== id);
}

onMounted(() => {
    fetchNotifications();
})
</script>

<template>
    <Head title="Notifications"/>
    <Page>
        <div
            class="w-full max-w-[1331px] md:min-h-[1077px] mx-auto p-[14px] md:p-12 md:border border-white/10 md:bg-[#393535A1] rounded-[20px] relative">
            <Link :href="route('home')">
                <Cross class="max-h-[18px] max-w-[18px] absolute top-3 md:top-[27px] right-5 md:right-[30px]"/>
            </Link>

            <div class="w-full mx-auto max-w-[1027px]">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="h2-2xsmall text-[#FAFAFB]">Notifications</h2>
                    <v-btn @click="readAll" color="transparent"
                           v-if="unreadNotifications?.length > 0"
                           class="markAllAsReadBtn text-sm text-[#9F9F9F]" style="box-shadow: none">
                        Mark all as read
                    </v-btn>
                </div>

                <div class="mt-6 w-full space-y-4">
                    <p class="text-[16px] leading-[24px] font-bold" v-if="hasUnreadNotifications">New</p>

                    <div class="space-y-4" v-show="hasUnreadNotifications">
                        <NotificationCard
                            v-for="(notification, key) in unreadNotifications"
                            :key
                            :notification
                            @destroy="handleDestroy"
                        />
                    </div>
                    <v-scroll-y-reverse-transition v-show="hasUnreadNotifications">


                    </v-scroll-y-reverse-transition>

                    <p class="text-[16px] leading-[24px] font-bold" v-if="notifications.readNotifications?.length > 0">
                        Old
                        Notifications</p>
                    <div class="space-y-4">
                        <NotificationCard
                            v-for="(notification, key) in readNotifications"
                            :key
                            :notification
                            @destroy="handleDestroy"
                        />
                    </div>
                </div>

                <h4 class="text-center fm-book py-3"
                    v-if="notifications.unreadNotifications?.length === 0 && notifications.readNotifications?.length === 0">
                    You didn't received any notifications yet !</h4>
            </div>


        </div>
    </Page>

</template>

<style scoped>
.markAllAsReadBtn {
    line-height: 24px !important;
}

@media (max-width: 768px) {
    .markAllAsReadBtn {
        display: none;
    }
}

</style>
