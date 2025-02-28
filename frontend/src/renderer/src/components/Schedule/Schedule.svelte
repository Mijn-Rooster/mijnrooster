<script lang="ts">
  import { TabItem, Tabs } from "flowbite-svelte";
  import type { UserModel } from "../../models/user.model";
  import ScheduleDayView from "./ScheduleDayView.svelte";
  import ScheduleWeekView from "./ScheduleWeekView.svelte";
  import { core } from "../../stores/core.store";

  let weekViewEnabled = $core.weekView;
  let activeTab = 0;

  export let user: UserModel;

  function toggleView() {
    activeTab = activeTab === 0 ? 1 : 0;
  }
</script>

{#if weekViewEnabled}
  <!-- Hidden button for numpad control -->
  <button class="hidden" data-numpad="toggle-view" on:click={toggleView}
    >Toggle View</button
  >

  <Tabs
    tabStyle="underline"
    class="text-center justify-center display:flex p-0 m-0"
    contentClass="pt-4 h-[calc(100dvh-350px)]"
  >
    <TabItem divClass="h-full" open={activeTab === 0}>
      <div slot="title" class="flex items-center gap-2 text-base">Dag</div>
      <ScheduleDayView {user} />
    </TabItem>
    <TabItem divClass="h-full" open={activeTab === 1}>
      <div slot="title" class="flex items-center gap-2 text-base">Week</div>
      <ScheduleWeekView {user} />
    </TabItem>
  </Tabs>
{:else}
  <div class="h-[calc(100dvh-300px)]">
    <ScheduleDayView {user} />
  </div>
{/if}
