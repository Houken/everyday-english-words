<script setup>
import dayjs from "dayjs";
import ja from 'dayjs/locale/ja';
dayjs.locale(ja);

import convertEnglish from './helpers'

const props = defineProps({
    'section': Number,
    'testTypeIsRead': Boolean,
    'testBookName': String,
    'testHintLevel': Number,
    'testQuestions': Number,
    'testRangeFrom': Number,
    'testRangeTo': Number,
    'splitTest': Array,
})
</script>

<template>
    <div id="wordSheet{{ section }}" class="border-b">
        <div id="wordsheetforprint1q" class="hidden p-4 bg-white rounded w-a4w h-a4h print:block">
            <div class="">
                <div class="flex flex-row text-xs">
                    <div class="flex flex-col"><div>{{ dayjs().format('YYYY/M/D(ddd) HH:mm') }}</div>
                    <div>{{ testBookName }}</div></div>

                    <h2 class="w-2/3 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ( {{ testRangeFrom }} 〜 {{
                        testRangeTo }} ) {{ section }} / {{ testQuestions / 25 }}</h2>
                        <p class="w-1/6 text-2xl italic text-right underline underline-offset-2" v-if="section == 1"><span class="text-4xl">/</span>{{ testQuestions }}</p>
                </div>
                <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="min-w-full text-xs font-light text-left">
                                    <thead
                                        class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                            <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                            <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="test in splitTest" :key="test.index_no"
                                            class="border-b dark:border-neutral-500">
                                            <td class="text-sm text-gray-300">□□□</td>
                                            <td class="px-6 py-1 text-right font-condensed whitespace-nowrap">{{ test.index_no }}</td>
                                            <td class="px-6 py-2 text-sm whitespace-nowrap">{{ convertEnglish(test.word.english, testTypeIsRead, testHintLevel) }}
                                            </td>
                                            <td class="px-6 py-1 whitespace-nowrap"><span
                                                    class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                        test.word.part_of_speech.charAt(0) }}</span>{{ testTypeIsRead ? '' : test.japanese }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="wordsheetforprint1a" class="hidden p-4 bg-white rounded w-a4w h-a4h print:block">
            <div class="">
                <div class="flex flex-row text-sm">
                    <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                        testRangeTo }}) {{ section }} / {{ testQuestions / 25 }} 正解</h2>
                </div>
                <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="min-w-full text-xs font-light text-left">
                                    <thead
                                        class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                        <tr>
                                            <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                            <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                            <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="test in splitTest" :key="test.index_no"
                                            class="border-b dark:border-neutral-500">
                                            <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                            <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                            <td class="px-6 py-1 whitespace-nowrap"><span
                                                    class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                        test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
