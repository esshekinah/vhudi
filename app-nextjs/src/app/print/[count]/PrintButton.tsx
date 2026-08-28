"use client";

export function PrintButton() {
  return (
    <div className="no-print mb-6 flex justify-end">
      <button className="vhudi-btn" type="button" onClick={() => window.print()}>
        Print / Save as PDF
      </button>
    </div>
  );
}
