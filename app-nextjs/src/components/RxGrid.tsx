import type { Order } from "@/lib/types";

/**
 * The SPH/CLYL/AXIS/P.D/SEG/RGD grid for RE and LE.
 * Field names match what the order actions read from FormData:
 *   RE: SPH, CLYL, AXIS, PD, SEG, RGD
 *   LE: SPH2, CLYL2, AXIS2, PD2, SEG2, RGD2
 */
const cols = ["SPH", "CLYL", "AXIS", "PD", "SEG", "RGD"] as const;
const headers = ["SPH", "CLYL", "AXIS", "P.D", "SEG", "R.G.D/ADD"];

export function RxGrid({ order }: { order?: Order }) {
  return (
    <table className="vhudi-table mt-2">
      <thead>
        <tr>
          <th></th>
          {headers.map((h) => (
            <th key={h}>{h}</th>
          ))}
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>RE</td>
          {cols.map((c) => (
            <td key={c}>
              <input
                className="vhudi-input"
                name={c}
                defaultValue={order ? (order[("r" + c) as keyof Order] as string) : ""}
              />
            </td>
          ))}
        </tr>
        <tr>
          <td>LE</td>
          {cols.map((c) => (
            <td key={c}>
              <input
                className="vhudi-input"
                name={c + "2"}
                defaultValue={order ? (order[("l" + c) as keyof Order] as string) : ""}
              />
            </td>
          ))}
        </tr>
      </tbody>
    </table>
  );
}
