<?php

namespace TuanAnh0907\Rake;

class Rake
{
    private $stopwords;

    private $paragraph;

    public function __construct($paragraph, $stopwords)
    {
        $this->paragraph = $paragraph;
        $this->stopwords = $stopwords;
    }

    public function extract(): array
    {
        $sentences = $this->split_sentences();

        $phrases_arr = $this->get_phrases($sentences);

        $scores = $this->get_scores($phrases_arr);

        $keywords = $this->get_keywords($phrases_arr, $scores);

        arsort($keywords);

        return $keywords;
    }

    /**
     * Split text into sentences with punctuation by special characters
     */

    public function split_sentences()
    {
        return preg_split('/(\.\D|, |,| - |\n|\(|\)|\"|\/)+/u', $this->paragraph);
    }

    /**
     * Split sentences into phrases by loaded stop words
     *
     * @param array $sentences Array of sentences
     */

    public function get_phrases(array $sentences = []): array
    {
        $phrases_arr = [];

        $regex = '/\b' . implode('\b|\b', $this->stopwords) . '\b/iu';
//        var_dump($regex);
//        die();
        foreach ($sentences as $sentence) {
            if (trim($sentence)) {
                $phraseItem = preg_replace($regex, "|", mb_strtolower(trim($sentence)));
                $phraseItem = explode("|", $phraseItem); 
                foreach ($phraseItem as $item) {
                    if (trim($item)) {
                        if (!is_numeric($item)) {
                            $phrases_arr[] = trim($item);
                        }
                    }
                }
            }
        }
        return $phrases_arr;
    }

    /**
     * @param string $phrase Phrase to be split into words
     */
    public static function split_phrase(string $phrase): array
    {
        return explode(' ', $phrase); 
    }

    /**
     * Calculate score for each word
     *
     * @param array $phrases_arr Array containing individual phrases
     */

    public function get_scores(array $phrases_arr): array
    {
        $frequencies = []; // tần suất
        $degrees = []; // bậc

        foreach ($phrases_arr as $p) {

            $words = self::split_phrase($p); 
            $words_count = count($words); 
            $words_degree = $words_count - 1;

            foreach ($words as $w) {
                $frequencies[$w] = $frequencies[$w] ?? 0;
                ++$frequencies[$w]; 
                $degrees[$w] = $degrees[$w] ?? 0;
                $degrees[$w] += $words_degree; 
            }

        }

        foreach ($frequencies as $word => $freq) {
            $degrees[$word] += $freq;
        }

        $scores = [];

        foreach ($frequencies as $word => $freq) {
            $scores[$word] = $scores[$word] ?? 0; 
            $scores[$word] = $degrees[$word] / (float)$freq;  
        }

        return $scores;
    }

    /**
     * Calculate score for each phrase by words scores
     *
     * @param array $phrases_arr Array of phrases (optimally) returned by get_phrases() method
     * @param array $scores Array of words and their scores returned by get_scores() method
     */
    public function get_keywords(array $phrases_arr, array $scores): array
    {
        $keywords = [];

        foreach ($phrases_arr as $phrases) {
            $keywords[$phrases] = $keywords[$phrases] ?? 0; 
            $words = self::split_phrase($phrases); 
            $score = 0;

            foreach ($words as $w) {
                $score += $scores[$w]; 
            }

            $keywords[$phrases] = $score; 
        }

        return $keywords;
    }
}
