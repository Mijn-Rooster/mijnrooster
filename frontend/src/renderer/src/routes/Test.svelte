<script lang="ts">
  import { Tabs, TabItem } from "flowbite-svelte";
  import { navigate } from "../stores/RouterStore";
  import { Button } from "flowbite-svelte";
</script>

<h1>Test Page</h1>
<p>It works!</p>

<br />

<Tabs>
  <TabItem open title="Profile">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      <b>Profile:</b>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
      incididunt ut labore et dolore magna aliqua.
    </p>
  </TabItem>
  <TabItem title="Settings">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      <b>Settings:</b>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
      incididunt ut labore et dolore magna aliqua.
    </p>
  </TabItem>
  <TabItem title="Users">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      <b>Users:</b>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
      incididunt ut labore et dolore magna aliqua.
    </p>
  </TabItem>
  <TabItem title="Dashboard">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      <b>Dashboard:</b>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
      incididunt ut labore et dolore magna aliqua.
    </p>
  </TabItem>
  <TabItem disabled>
    <span slot="title" class="text-gray-400 dark:text-gray-500">Disabled</span>
    <p class="text-sm text-gray-500 dark:text-gray-400">
      <b>Disabled:</b>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
      incididunt ut labore et dolore magna aliqua.
    </p>
  </TabItem>
</Tabs>

<Button on:click={() => navigate("/")} class="mt-4">Go to home page</Button>
