<script setup>
import {defineAsyncComponent, ref, watch} from "vue";
import CommunitySidebar from "@/Pages/Community/Partials/CommunitySidebar.vue";
import {Head, useForm} from '@inertiajs/vue3';
const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));

const props = defineProps({
    video: Object,

})

const publishForm = useForm({
    published: props.video.published,
})

const bannerForm = useForm({
    banner: props.video.banner,
})

const featuredForm = useForm({
    featured: props.video.featured,
})




</script>

<template>
    <Head title="videos"/>

    <Page class="custom-community-scroll">
        <div class="grid-community">
            <CommunitySidebar />
            <div class="overflow-auto">
                <div>

                    <h3 v-if="video.processing">Video is processing.... Please avoid to edit for now</h3>

                    <div class="cards-bg">
                        <div class="flex items-center justify-end gap-3">

                            <div class="flex items-center justify-end gap-2">
                                <p>Published</p>
                                <div class="toggle-switch">
                                    <input v-model="video.published" class="toggle-input disabled" id="published" type="checkbox" disabled>
                                    <label class="toggle-label" for="published"></label>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <p>Banner</p>
                                <div class="toggle-switch">
                                    <input v-model="video.banner" class="toggle-input disabled" id="banner" type="checkbox" disabled>
                                    <label class="toggle-label" for="banner"></label>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <p>Featured</p>
                                <div class="toggle-switch">
                                    <input v-model="video.featured" class="toggle-input disabled" id="featured" type="checkbox" disabled>
                                    <label class="toggle-label" for="featured"></label>
                                </div>
                            </div>
                        </div>


                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-sm text-gray-700 uppercase  bg-[#393535a1]  dark:text-gray-400">
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.title ?? 'N/A' }}
                                </th>
                            </tr>
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.description ?? 'N/A' }}
                                </th>
                            </tr>
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.location ?? 'N/A' }}
                                </th>
                            </tr>
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Duration
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.duration ?? 'N/A' }}
                                </th>
                            </tr>
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Creator
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.channel?.name ?? 'N/A' }}
                                </th>
                            </tr>
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Country
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.country?.emoji }} {{ video.country?.name ?? 'N/A' }}
                                </th>
                            </tr>
                            <tr class="border-b-[1px] border-b-[#393535a1]">
                                <th scope="col" class="px-6 py-3 text-white">
                                    Short
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ video.short ?? 'N/A' }}
                                </th>
                            </tr>
                            </thead>

                        </table>

                        <!-- ----------------------  -->
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-5">
                            <div>
                                <h3 class="font-700 mb-2">Thumbnail</h3>
                                <img :src="video.thumbnail || placeholderIcon" alt="" class="series-thumbnail-dimen">
                            </div>
                        </div>
                        <!-- ----------------------  -->
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-5">
                            <div>
                                <h3 class="font-700 mb-2">Original Video</h3>
                                <video preload="metadata" controls class="series-trailor-shows">
                                    <source :src="video.url" type="video/mp4"/>
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div>
                                <h3 class="font-700 mb-2">Video 480</h3>
                                <video preload="metadata" controls class="series-trailor-shows">
                                    <source :src="video.url_480" type="video/mp4"/>
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div>
                                <h3 class="font-700 mb-2">Video 720</h3>
                                <video preload="metadata" controls class="series-trailor-shows">
                                    <source :src="video.url_720" type="video/mp4"/>
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div>
                                <h3 class="font-700 mb-2">Video 1080</h3>
                                <video preload="metadata" controls class="series-trailor-shows">
                                    <source :src="video.url_1080" type="video/mp4"/>
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                        <!-- ----------------------  -->
                        <!-- ----------------------  -->

                    </div>



                </div>

            </div>
        </div>
    </Page>
</template>
