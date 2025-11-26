<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecisionSession;
use App\Models\Criterion;
use App\Models\Alternative;
use App\Models\Evaluation;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DecisionController extends Controller
{
    public function index()
    {
        return view('decision.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'name' => 'required|string|max:255'
        ]);

        $path = $request->file('file')->getRealPath();

        // Load Excel file
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Create new session
        $session = DecisionSession::create(['name' => $request->name]);

        // Assume first row = headers
        $headers = array_map('trim', $rows[0]);

        // Remove first row
        unset($rows[0]);

        // Save criteria based on headers (skip first column "Nama Produk")
        $criteriaHeaders = array_slice($headers, 1);
        $criteria = [];

        foreach ($criteriaHeaders as $header) {
            $criteria[] = Criterion::create([
                'session_id' => $session->id,
                'name' => $header,
                'type' => 'benefit', // default, will allow user to edit later
                'weight' => 0
            ]);
        }

        // Save alternatives + evaluations
        foreach ($rows as $row) {
            $alternative = Alternative::create([
                'session_id' => $session->id,
                'name' => $row[0],
            ]);

            foreach (array_slice($row, 1) as $index => $value) {
                Evaluation::create([
                    'alternative_id' => $alternative->id,
                    'criterion_id' => $criteria[$index]->id,
                    'value' => floatval($value),
                ]);
            }
        }

        return redirect()->route('decision.view', $session->id)
                        ->with('success', 'File imported successfully!');
    }

    public function view(DecisionSession $session)
    {
        $criteria = $session->criteria;
        $alternatives = $session->alternatives()->with('evaluations')->get();

        return view('decision.view', compact('session', 'criteria', 'alternatives'));
    }

    public function weights(DecisionSession $session)
{
    $criteria = $session->criteria;
    return view('decision.weights', compact('session', 'criteria'));
}

public function calculate(Request $request, DecisionSession $session)
{
    // 1️⃣ Update weights & types
    foreach ($session->criteria as $criterion) {
        $criterion->update([
            'weight' => $request->weights[$criterion->id],
            'type' => $request->types[$criterion->id],
        ]);
    }

    // 2️⃣ Fetch data
    $criteria = $session->criteria;
    $alternatives = $session->alternatives()->with('evaluations')->get();

    // Build decision matrix
    $matrix = [];
    foreach ($alternatives as $alt) {
        foreach ($criteria as $crit) {
            $matrix[$alt->id][$crit->id] = $alt->evaluations
                ->firstWhere('criterion_id', $crit->id)->value;
        }
    }

    // 3️⃣ Normalize
    $norm = [];

    foreach ($criteria as $crit) {
        $sumSquares = 0;

        // compute denominator = sqrt(sum(value²))
        foreach ($alternatives as $alt) {
            $sumSquares += pow($matrix[$alt->id][$crit->id], 2);
        }

        $denominator = sqrt($sumSquares ?: 1); // avoid divide-by-zero

        // normalize each alternative
        foreach ($alternatives as $alt) {
            $norm[$alt->id][$crit->id] = $matrix[$alt->id][$crit->id] / $denominator;
        }
    }


    // 4️⃣ Weighted normalized matrix
    $weighted = [];
    foreach ($criteria as $crit) {
        foreach ($alternatives as $alt) {
            $weighted[$alt->id][$crit->id] = $norm[$alt->id][$crit->id] * $crit->weight;
        }
    }

    // 5️⃣ Ideal best/worst
    $idealBest = [];
    $idealWorst = [];

    foreach ($criteria as $crit) {
        // collect all weighted values for this criterion
        $values = array_map(fn($altId) => $weighted[$altId][$crit->id], array_keys($weighted));

        if ($crit->type === 'benefit') {
            $idealBest[$crit->id] = max($values);
            $idealWorst[$crit->id] = min($values);
        } else {
            $idealBest[$crit->id] = min($values);
            $idealWorst[$crit->id] = max($values);
        }
    }


    // 6️⃣ Distance to ideal
    $distanceBest = [];
    $distanceWorst = [];

    foreach ($alternatives as $alt) {
        $sumBest = 0;
        $sumWorst = 0;

        foreach ($criteria as $crit) {
            $sumBest += pow($weighted[$alt->id][$crit->id] - $idealBest[$crit->id], 2);
            $sumWorst += pow($weighted[$alt->id][$crit->id] - $idealWorst[$crit->id], 2);
        }

        $distanceBest[$alt->id] = sqrt($sumBest);
        $distanceWorst[$alt->id] = sqrt($sumWorst);
    }


    // 7️⃣ Calculate TOPSIS score (Closeness coefficient)
        $scores = [];
        foreach ($alternatives as $alt) {
            $scores[$alt->id] = $distanceWorst[$alt->id] / ($distanceBest[$alt->id] + $distanceWorst[$alt->id]);
        }

        // Sort by score desc
        arsort($scores);

        return view('decision.results', compact('session', 'criteria', 'alternatives', 'scores'));
    }

        public function results(DecisionSession $session)
    {
        $criteria = $session->criteria;
        $alternatives = $session->alternatives()->with('evaluations')->get();

        // You can reload saved results from DB if you decide to store them later
        return view('decision.results', compact('session', 'criteria', 'alternatives'));
    }

    public function history()
    {
        $sessions = \App\Models\DecisionSession::orderBy('created_at', 'desc')->get();

        return view('decision.history', compact('sessions'));
    }

}
