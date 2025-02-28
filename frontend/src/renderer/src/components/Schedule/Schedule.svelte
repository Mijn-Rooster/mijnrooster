<script lang="ts">
  import { TabItem, Tabs } from "flowbite-svelte";
  import type { UserModel } from "../../models/user.model";
  import ScheduleDayView from "./ScheduleDayView.svelte";
  import ScheduleWeekView from "./ScheduleWeekView.svelte";
  import { core } from "../../stores/core.store";

  let weekViewEnabled = $core.weekView;

  export let user: UserModel;
</script>

{#if weekViewEnabled}
  <Tabs
    tabStyle="underline"
    class="text-center justify-center display:flex p-0 m-0"
    data-numpad="toggle-view"
    contentClass="pt-4 h-[calc(100dvh-350px)]"
  >
    <TabItem open divClass="h-full">
      <div slot="title" class="flex items-center gap-2 text-xl">Dag</div>
      <ScheduleDayView {user}/>
    </TabItem>
    <TabItem divClass="h-full">
      <div slot="title" class="flex items-center gap-2 text-xl">Week</div>
      <ScheduleWeekView {user} />
    </TabItem>
  </Tabs>
{:else}
<div class="h-[calc(100dvh-300px)]">
  <ScheduleDayView {user} />
</div>
{/if}
