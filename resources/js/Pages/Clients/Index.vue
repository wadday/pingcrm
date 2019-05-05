<template>
  <layout title="Clients">
    <h1 class="mb-8 font-bold text-3xl">Clients</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-sm mr-4" @reset="reset">
        <label class="block text-grey-darkest">Trashed:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <inertia-link class="btn-indigo" :href="route('clients.create')">
        <span>Create</span>
        <span class="hidden md:inline">Client</span>
      </inertia-link>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Name</th>
          <th class="px-6 pt-6 pb-4">Phone</th>
          <th class="px-6 pt-6 pb-4">District</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Address</th>
        </tr>
        <tr v-for="client in clients.data" :key="client.id" class="hover:bg-grey-lightest focus-within:bg-grey-lightest">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo" :href="route('clients.edit', client.id)">
              {{ client.first_name }} {{ client.last_name }}
              <icon v-if="client.deleted_at" name="trash" class="flex-no-shrink w-3 h-3 fill-grey ml-2" />
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('clients.edit', client.id)" tabindex="-1">
              {{ client.phone }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('clients.edit', client.id)" tabindex="-1">
              {{ client.region }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('clients.edit', client.id)" tabindex="-1">
              {{ client.address }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('clients.edit', client.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-grey" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="clients.data.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No clients found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="clients.links" />
  </layout>
</template>

<script>

import _ from 'lodash'
import Layout from '@/Shared/Layout'
import SearchFilter from '@/Shared/SearchFilter'
import Icon from '@/Shared/Icon'
import Pagination from '@/Shared/Pagination'

export default {
  name: 'Index',
  components: {Pagination, Icon, SearchFilter, Layout},

  props: {
    clients: Object,
    filters: Object
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      handler: _.throttle(function() {
        let query = _.pickBy(this.form)
        this.$inertia.replace(this.route('clients', Object.keys(query).length ? query : { remember: 'forgot' }))
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = _.mapValues(this.form, () => null)
    },
  },
}
</script>
