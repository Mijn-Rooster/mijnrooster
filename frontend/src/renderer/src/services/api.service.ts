import type { ScheduleItemModel } from "../models/scheduleItem.model";

export async function retrieveSchedule(
  user: string | number,
  todayStartUnix: number,
  todayEndUnix: number
): Promise<ScheduleItemModel[]> {
  const url = `http://localhost:8000/v1/schedule/${user}?start=${todayStartUnix}&end=${todayEndUnix}`;

  const response = await fetch(url, {
    method: "GET",
    headers: {
      accept: "application/json",
      Authorization: "Bearer oqkd1ogtDkOUcsa33HOdXvt76uXiTdfwxYGMqWem",
    },
  });

  if (!response.ok) {
    console.error("Schedule fetch failed:", response.status);
    return [];
  }

  const responsedata = await response.json();
  responsedata.data.sort((a: any, b: any) => a.start - b.start);
  return responsedata.data;
}