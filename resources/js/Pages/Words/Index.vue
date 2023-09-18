<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const page = usePage();

const props = defineProps({
    'books': Array,
    'words': Object,
})
// const books = ref(page.props.books);
const selectedBookId = ref(page.props.selectedBookId);

const changeBook = () => {
    router.get(route('words.index', { book_id: selectedBookId.value }));
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
                    <div id="book-select">
                        <select v-model="selectedBookId" @change="changeBook" class="my-2">
                            <option disabled>単語帳を選択</option>
                            <option v-for="book in books" :key="book.id" :value="book.id">{{ book.name }}</option>
                        </select>
                        <!-- /# -->
                    </div>
                    <div class="p-4">
                        <table class="w-full text-sm text-left text-gray-500 bg-white border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-2 font-medium text-gray-900">id</th>
                                    <th scope="col" class="px-6 py-2 font-medium text-gray-900">単語</th>
                                    <th scope="col" class="px-6 py-2 font-medium text-gray-900">品詞</th>
                                    <th scope="col" class="px-6 py-2 font-medium text-gray-900">意味</th>
                                    <th scope="col" class="px-6 py-2 font-medium text-gray-900"></th>
                                </tr>
                            </thead>
                            <tbody class="border-t border-gray-100 divide-y divide-gray-100">
                                <tr class="odd:bg-white even:bg-gray-50" v-for="word in props.words.data" :key="word.meanings[0].index_no">
                                    <td class="px-6 py-2">{{ word.meanings[0].index_no }}</td>
                                    <td class="px-6 py-2">{{ word.english }}</td>
                                    <td class="px-6 py-2">{{ word.part_of_speech }}</td>
                                    <td class="px-6 py-2">{{ word.meanings[0].japanese }}</td>
                                    <td class="flex justify-end gap-4 px-6 py-2 font-medium"><a href=""
                                            class="text-primary-700">Edit</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination class="mt-6" :links="props.words.links"></Pagination>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
