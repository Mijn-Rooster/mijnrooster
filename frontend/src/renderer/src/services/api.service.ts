import type { ScheduleItemModel } from "../models/scheduleItem.model";
import type { User } from "../stores/UserStore";

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

export async function retrieveUserInfo(
  schoolInSchoolYear: string,
  leerlingnummer: string
): Promise<User | null> {
  const url = `http://localhost:8000/v1/school/${schoolInSchoolYear}/user/${leerlingnummer}`;

  const response = await fetch(url, {
    method: "GET",
    headers: {
      accept: "application/json",
    },
  });

  if (!response.ok) {
    console.error("User info fetch failed:", response.status);
    return null;
  }

  const data = await response.json();
  return {
    fullName: `${data.firstName} ${data.prefix} ${data.lastName}`.trim(),
    isLoggedIn: true,
    leerlingnummer: data.code,
    schoolInSchoolYear: schoolInSchoolYear,
    firstName: data.firstName,
    prefix: data.prefix,
    lastName: data.lastName,
    code: data.code
  };
}