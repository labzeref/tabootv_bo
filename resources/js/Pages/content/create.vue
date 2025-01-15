<script setup>
import { defineAsyncComponent, ref } from "vue";
import CommunitySidebar from "@/Pages/Community/Partials/CommunitySidebar.vue";
import {Head, Link, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import ChunkableFileInput from "@/Components/Inputs/ChunkableFileInput.vue";
const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));

const props = defineProps({
    countries: Array,
    short:Boolean
})

const form = useForm({
    title: '',
    description: '',
    location: '',
    duration: '',
    country: '',
    thumbnail: null,
    video: null,
    short: props.short,
})

const submit = () => {
    form.post(route('contents.videos.store'),{
        onSuccess : () => {
            form.reset();
        }
    })
}
</script>

<template>
    <Head title="videos"/>

    <Page class="custom-community-scroll">
        <div class="grid-community">
            <CommunitySidebar />
            <div class="overflow-auto">
                <form @submit.prevent="submit">
                    <div class="col-span-12 xl:col-span-8">
                        <div class="cards-bg mb-[25px]">
                            <VFileInput :required="false" name="thumbnail" label="Thumbnail"
                                        @change="(e) => form.thumbnail = e.target.files[0]"
                                        :errorMessage="form.errors.thumbnail"/>
                            <p style="    color: var(--primary-color);">{{form.errors.thumbnail}}</p>
                        </div>
                        <div class="cards-bg mb-[25px]">
                            <ChunkableFileInput :required="false" name="video" label="Video"
                                                @uploaded="(id) => form.video = id" @clear="form.video = null"/>
                            <span style="    color: var(--primary-color);">{{form.errors.video}}</span>

                        </div>
                        <div class="cards-bg mb-[25px]">
                            <TextInput :required="false" name="title" label="Title"
                                       placeholder="Type Title" v-model="form.title"
                                       :errorMessage="form.errors.title"/>
                        </div>
                        <div class="cards-bg mb-[25px]">
                            <TextInput :required="false" name="description" label="Description"
                                       placeholder="Type Description" v-model="form.description"
                                       :errorMessage="form.errors.description"/>
                        </div>
                        <div class="cards-bg mb-[25px]">
                            <TextInput :required="false" name="location" label="Location"
                                       placeholder="Type Location" v-model="form.location"
                                       :errorMessage="form.errors.location"/>
                        </div>
                        <div class="cards-bg mb-[25px]">
                            <TextInput :required="false" name="duration" label="Duration"
                                       placeholder="Type Duration in seconds" v-model="form.duration"
                                       input-type="number"
                                       :errorMessage="form.errors.duration"/>
                        </div>
                        <div class="cards-bg mb-[25px]">
                            <label for="country"
                                   class="flex flex-col space-y-3 text-[14px] mb-[25px] w-full max-h-[84px]">
                                <span class="font-medium fm-book">Country <span style="    color: var(--primary-color);">{{form.errors.country}}</span> </span>

                                <v-combobox
                                    :autocomplete="false"
                                    name="country"
                                    density="default"
                                    variant="plain"
                                    :auto-select-first="true"
                                    :items="countries"
                                    item-title="name_with_flag"
                                    item-value="id"
                                    v-model="form.country"
                                    :returnObject="false"
                                >
                                </v-combobox>
                            </label>

                        </div>
                        <button class="btn btn-primary btn-sm max-w-[257px] w-full mb-[25px]">Create</button>
                    </div>
                </form>

            </div>
        </div>
    </Page>
</template>
