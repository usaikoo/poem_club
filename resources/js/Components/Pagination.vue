<script lang="ts"setup>
import { Link } from '@inertiajs/vue3'
//interface link
interface LinkType {
    label: string;
    url: string | null;
}

const props = defineProps<{ links: LinkType[] }>();

</script>

<template>
    <nav class="flex justify-center item-center space-x-2 my-4">
        <div v-for="(link, index) in links" :key="index">
            <Link v-if="link.url" :href="link.url" 
            class="px-4 py-2 rounded hover:bg-gray-300" 
            :class="{'bg-blue-500 text-white' : link.active}" v-html="link.label" >
                </Link>
            <span v-else class="px-4 py-2 rounded bg-gray-300 text-gray-500" v-html="link.label"> </span>
        </div>
    </nav>
</template>