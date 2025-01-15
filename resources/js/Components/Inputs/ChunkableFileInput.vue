<script setup>
import {router, useForm, usePage} from "@inertiajs/vue3";
import {computed, ref} from "vue";

defineProps({
    name: String,
    label: String,
    required: {
        type: Boolean,
        default: false
    },
})

const emit = defineEmits(['clear', 'uploaded', 'startUploading']);

const status = ref('idle');
const input = ref(null)
const videoFile = ref(null)
const videoPreviewUrl = ref('')
const abortController = ref(null);
const mediaUuid = ref(null);
const progressArray = ref({});
const progress = ref(0);

const inputText = computed(() => {
    switch (status.value) {
        case 'pending':
            return `Uploading [${progress.value}%]`;
        case 'completed':
            return "Video is uploaded";
        default:
            return "Upload Video";

    }
})

const inputTextClass = computed(() => {
    switch (status.value) {
        case 'pending':
            return "text-white";
        case 'completed':
            return "text-success";
        default:
            return "text-white";

    }
})

const handleVideoChange = async (e) => {
    const file = e.target.files[0]

    if (file && file.type.startsWith('video/')) {
        status.value = 'pending';
        emit('startUploading')
        videoPreviewUrl.value = URL.createObjectURL(file);
        abortController.value = new AbortController();
        await uploadFileInChunks(file)
    } else {
        alert('Please upload a video file.')
    }
}

const uploadFileInChunks = async (file) => {
    const chunkSize = 1024 * 1024 * 7;
    const fileExtension = file.name.split('.').pop()
    const totalChunks = Math.ceil(file.size / chunkSize);
    const chunkQueue = [];

    for (let i = 0; i < totalChunks; i++) {
        const start = i * chunkSize;
        const offset = (i + 1) * chunkSize;
        const end = Math.min(file.size, start + chunkSize);
        const chunk = file.slice(start, end);
        progressArray.value[i] = 0;
        chunkQueue.push({chunk, offset, index: i, chunk_count: totalChunks, size: file.size});
    }

    await getMediaUuid();
    await parallelUploads(chunkQueue);

    async function getMediaUuid() {
        await axios.post(route('temp_media.get-uuid'), {
            total_chunks: totalChunks,
            file_extension: fileExtension
        }).then(response => {
            mediaUuid.value = response.data.media_uuid;
        })
    }

    async function parallelUploads(queue) {
        const tasks = queue.splice(0, 5).map(uploadChunk);
        await Promise.all(tasks);
        if (queue.length > 0) {
            await parallelUploads(queue);
        }
    }

    async function uploadChunk({chunk, index, offset, chunk_count}) {
        const formData = new FormData();
        formData.append('chunk', chunk);
        formData.append('index', index);
        formData.append('offset', offset);
        formData.append('chunk_count', chunk_count);
        formData.append('media_uuid', mediaUuid.value);

        try {
            await axios.post(route('temp_media.upload'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                signal: abortController.value.signal,
                onUploadProgress: (progressEvent) => {
                    progressArray.value[index] = Math.round(progressEvent.loaded);

                    const totalLoaded = Object.values(progressArray.value).reduce((acc, curr) => acc + curr, 0);
                    progress.value = Math.round((totalLoaded / file.size) * 100);
                }
            }).then(response => {
                console.log('chunk response', response.data.chucking_completed);
                if (response.data.chucking_completed) {
                    emit('uploaded', response.data.media_uuid)
                    status.value = 'completed';
                }
            });
        } catch (error) {
            console.log(error)
        }
    }
}

const clearVideo = () => {
    // if (mediaUuid.value) {
    //     axios.delete(route('temp_media.destroy', mediaUuid.value))
    // }
    reset();
}

const reset = () => {
    emit('clear');
    videoFile.value = null
    videoPreviewUrl.value = null
    status.value = '';
    input.value.value = null;

    if (abortController.value) {
        abortController.value.abort();
        abortController.value = null;
    }
}

defineExpose({
    reset
});
</script>

<template>
    <div>
        <div v-if="videoPreviewUrl" class="mb-4 relative">
            <video class="w-full h-[300px] object-cover rounded-[12px] mt-4" controls>
                <source :src="videoPreviewUrl" type="video/mp4"/>
                Your browser does not support the video tag.
            </video>
            <div class="absolute top-4 right-4 cursor-pointer talha" @click="clearVideo">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 21L21 3" stroke="white" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round"/>
                    <path d="M21 21L3 3" stroke="white" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <VFileInput :required :name :label @change="handleVideoChange" @click:clear="reset"/>
        <p>{{inputText}}</p>
    </div>
</template>

<style scoped>
.hidden {
    display: none;
}

.cursor-pointer {
    cursor: pointer;
}

.mb-4 {
    margin-bottom: 1rem;
}

/* Add more CSS as required */
</style>
