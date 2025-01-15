<script setup>
import { defineAsyncComponent, ref } from "vue";
import CommunitySidebar from "@/Pages/Community/Partials/CommunitySidebar.vue";
import editIcon from "@/assets/edit-icon.svg";
import viewIcon from "@/assets/view-icon.svg";
import deleteIcon from "@/assets/delete-icon.svg";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link } from '@inertiajs/vue3';


const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));


defineProps({
    videos: Array,
    short:Boolean,
})
const deletableVideoId = ref(null)
const showDeleteModal = ref(false)
const handleDelete = (_id) => {
    deletableVideoId.value = _id
    showDeleteModal.value = true
}

const cancelDelete = () => {
    deletableVideoId.value = null
    showDeleteModal.value = false
}
</script>

<template>
    <Head title="videos"/>

    <Page class="custom-community-scroll">
        <div class="grid-community">
            <CommunitySidebar />
            <div class="overflow-auto">

                <div class="flex justify-end mb-6">
                    <Link :href="short ?route('contents.shorts.create'):route('contents.videos.create')">
                        <PrimaryButton class="btn btn-primary btn-sm">Create</PrimaryButton>
                    </Link>
                </div>
                <!-- Render posts -->
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-[#393535a1]   dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Thumbnail
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Publish At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(video, key) in videos.data"
                            class="bg-[#9f9f9f4f] border-b  dark:border-gray-700  fw-bold text-white whitespace-nowrap dark:text-white">
                            <th scope="row" class="px-6 py-4 fw-bold text-white whitespace-nowrap dark:text-white">
                                <img class="size-12" :src="video.thumbnail" alt="">
                            </th>
                            <td class="px-6 py-4">
                                {{ video.title }}
                            </td>
                            <td class="px-6 py-4">
                                {{ video.humans_publish_at ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right flex items-center gap-3">
                                <Link :href="short?route('contents.shorts.edit',video.id): route('contents.videos.edit',video.id)" class="fw-bold text-primary   hover:underline">
                                    <img :src="editIcon" alt="" class="h-4 w-4"></Link>
                                <Link :href="route('contents.videos.show',video.id)" class="fw-bold text-primary   hover:underline">
                                    <img :src="viewIcon" alt="" class="h-5 w-5">
                                </Link>
                                <button v-if="!video.published_at" class="fw-bold text-primary   hover:underline" @click="handleDelete(video.id)">
                                    <img :src="deleteIcon" alt="" class="h-5 w-5">
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                <Pagination :links="videos.links"/>

                <v-dialog max-width="500" v-model="showDeleteModal">
                    <v-card title="Confirm deletion of Record">
                        <v-card-text>
                            Are you sure want to delete this record?
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <Link :href="deletableVideoId ? route('contents.video.delete', deletableVideoId) : '#'" method="DELETE"
                                  :preserveState="false">
                                <v-btn
                                    class="btn btn-primary"
                                    text="Yes Delete"
                                ></v-btn>
                            </Link>

                            <v-btn
                                class="btn btn-secondary"
                                text="Cancel"
                                @click="cancelDelete"
                            ></v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>




            </div>
        </div>
    </Page>
</template>
