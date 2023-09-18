<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const page = usePage();

const props = defineProps({
    'selectedBookId': Number,
    'meanings': Object,
})

const bookId = ref(page.props.selectedBookId);

const changeBook = () => {
    router.get(route('meaning.index', { book_id: bookId.value }));
}
</script>

<template>
    <Head title="単語一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">単語一覧</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">単語の一覧</div>
                    <div class="p-4">
                        <!-- <p>{{ bookId }}</p> -->
                        <!-- <p>{{ meanings }}</p> -->
                        <select v-model="bookId" @change="changeBook">
                            <option value=1>ターゲット1900</option>
                            <option value=2>ターゲット1400</option>
                            <option value=3>中学ターゲット1800</option>
                            <option value=4>システム2000</option>
                        </select>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            単語
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            品詞
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            意味
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="meaning in props.meanings.data">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ meaning.word.english }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ meaning.word.part_of_speech }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ meaning.japanese }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="#"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination class="mt-6" :links="props.meanings.links"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
