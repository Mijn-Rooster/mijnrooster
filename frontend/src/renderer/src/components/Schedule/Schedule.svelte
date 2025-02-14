<script lang="ts">
  import ScheduleDayView from "./ScheduleDayView.svelte";
  import ScheduleWeekView from "./ScheduleWeekView.svelte";
  import { ButtonGroup, Button } from "flowbite-svelte";
  import { navigate } from "../../stores/router.store";
  import { Tabs, TabItem } from "flowbite-svelte";
  import type { UserModel } from "../../models/user.model";

  export let user: UserModel;
</script>

<ScheduleDayView {user} />

<!--<Tabs tabStyle="full" defaultClass="flex rounded-lg divide-x rtl:divide-x-reverse divide-gray-200">
  <TabItem open class="w-full">
    <span slot="title">Dag</span>
    <ScheduleDayView />
  </TabItem>
  <TabItem class="w-full">
    <span slot="title">Week</span>
    <ScheduleWeekView />
  </TabItem>
</Tabs>-->
