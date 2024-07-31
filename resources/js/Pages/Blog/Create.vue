<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { ref } from 'vue';
import { Plus } from '@element-plus/icons-vue';

import type { UploadProps, UploadUserFile } from 'element-plus';
import { useForm } from '@inertiajs/vue3';

// Initial file list with one image for demonstration
const fileList = ref<UploadUserFile[]>([
    
]);

const dialogImageUrl = ref('');
const dialogVisible = ref(false);

// Function to handle removing images from the UI
const handleRemove: UploadProps['onRemove'] = (uploadFile, uploadFiles) => {
    console.log(uploadFile, uploadFiles);
    // Remove the selected file from the fileList
    fileList.value = uploadFiles;
};

// Function to handle image preview in a dialog
const handlePictureCardPreview: UploadProps['onPreview'] = (uploadFile) => {
    dialogImageUrl.value = uploadFile.url!;
    dialogVisible.value = true;
};


// Form data with type annotations
interface FormData {
    title: string;
    content: string;
    image: File | null;

}


//Initialize from data with correct types
const form = useForm<FormData>({
    title: '',
    content: '',
    image:null,
})


const handleFileChange = (file: UploadUserFile) => {
    console.log(`this is file seleted ${file.raw}`)
    if (file.status === 'ready') {
        form.image = file.raw as File;
    } else {
        form.image = null;
    }
}

// submit form 

const submit = () => {
    form.post('/blog')
}
</script>

<template>
    <AuthenticatedLayout>
        <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
                <div class="flex flex-col text-center w-full mb-12">
                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Create new post</h1>
                    <p class="lg:w-2/3 mx-auto leading-relaxed text-base">How are you feeling today?</p>
                </div>

                <!-- form inputs -->
                <form @submit.prevent ="submit">

                    <div class="lg:w-1/2 md:w-2/3 mx-auto">
                        <div class="flex flex-wrap -m-2">
                            <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">Title</label>
                                    <input v-model="form.title" type="text" id="title" name="title"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                </div>
                            </div>

                            <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="message" class="leading-7 text-sm text-gray-600">Poem</label>
                                    <textarea v-model="form.content" id="message" name="message"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                </div>
                            </div>

                            <!-- Image upload UI -->
                            <el-upload :on-change="handleFileChange" v-model:file-list="fileList" :auto-upload="false"
                                action="https://run.mocky.io/v3/9d059bf9-4660-45f2-925d-ce80ad6c4d15"
                                list-type="picture-card" :on-preview="handlePictureCardPreview"
                                :on-remove="handleRemove">
                                <el-icon>
                                    <Plus />
                                </el-icon>
                            </el-upload>

                            <!-- Image preview dialog -->
                            <el-dialog v-model:visible="dialogVisible">
                                <img class="w-full" :src="dialogImageUrl" alt="Preview Image" />
                            </el-dialog>

                            <div class="p-2 w-full">
                                <button 
                                    class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>


                </form>


                <!-- end -->
            </div>
        </section>
    </AuthenticatedLayout>
</template>
