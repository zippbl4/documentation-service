<template>
    <Card class="flex flex-col items-center justify-center">
        <div class="overflow-hidden overflow-x-auto relative">
            <table
                class="w-full divide-y divide-gray-100 dark:divide-gray-700"
                data-testid="resource-table"
            >
                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2">Пользователь</th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2">Всего правок</th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2">Правок одобрено</th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2">Правок отклонено</th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2">Изменено страниц</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <tr v-for="row in this.card.statistic">
                    <!-- TODO -->
                    <td><a
                        class="link-default"
                        v-bind:href="'/nova/resources/users/' + row.user_id"> {{ row.user_name }}
                    </a>
                    </td>
                    <td>{{ row.all_edits }}</td>
                    <td>{{ row.approved_yes }} ({{ row.approved_yes_pc }} %)</td>
                    <td>{{ row.approved_no }} ({{ row.approved_no_pc }} %)</td>
                    <td>{{ row.edit_pages }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </Card>
</template>

<script>
export default {
    props: [
        'card',
        'statistic',
    ],
    mounted() {
        console.log(this.card);
    },
}
</script>
